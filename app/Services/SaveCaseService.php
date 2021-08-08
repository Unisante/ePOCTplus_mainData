<?php

namespace App\Services;

use App\Answer;
use App\Diagnosis;
use App\Drug;
use App\Formulation;
use App\HealthFacility;
use App\Management;
use App\Node;
use App\Patient;
use App\PatientConfig;
use App\Version;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Formula;
use UnexpectedValueException;

class SaveCaseService
{
  protected $caseData;
  protected $algorithm;
  protected $version;
  protected $patientConfig;

  /**
   * Save a medical case on the database
   *
   * @param object $caseData
   * @return MedicalCase
   */
  public function save($caseData)
  {
    self::checkHasProperties($caseData, ['patient', 'nodes', 'diagnosis', 'version_id']);
    self::checkHasProperties($caseData['patient'], ['group_id']);

    $hf = $this->updateHf($caseData['patient']['group_id']);

    $versionId = $caseData['version_id'];

    $version = Version::where('medal_c_id', $versionId)->first();
    $config = null;

    if ($version) {
      $config = PatientConfig::where('version_id', $version->id)->first();
    } else {
      $data = $this->getVersionData($hf, $versionId);
      $versionData = $data['medal_r_json'];
      $configData = $data['medal_data_config'];
      $version = $this->updateVersion($versionData);
      $config = $this->updateConfig($configData, $version);
    }

    $patient = $this->savePatient($caseData, $config, $version);
    $case = $this->saveCase($caseData, $version, $patient);

    return $case;
  }

  protected function getVersionData($hf, $versionId)
  {
    if (Config::get('medal.global.local_health_facility_management')) {
      $versionJson = $hf->versionJson;

      if ($versionJson === null) {
        throw new UnexpectedValueException("Health facility $hf->group_id has no associated version");
      }

      return json_decode($versionJson->json, true);
    } else {
      return json_decode(Http::get(Config::get('medal.urls.creator_algorithm_url') . $versionId), true);
    }
  }

  /**
   * Update the patient config from medalc
   *
   * @param Version $version
   * @return PatientConfig
   */
  public function updateConfig($configData, $version)
  {
    return (new PatientConfigLoader($configData, $version))->load();
  }

  /**
   * Retrieve the health facility from the database
   *
   * @param int $groupId
   * @return HealthFacility
   */
  public function updateHf($groupId)
  {
    $hf = HealthFacility::where('group_id', $groupId)->first();

    if ($hf === null) {
      if (Config::get('medal.global.local_health_facility_management')) {
        throw new UnexpectedValueException("Health facility with group_id $groupId not found in database");
      } else {
        $data = Http::get(Config::get('medal.urls.creator_health_facility_url') . $groupId);
        $hfData = json_decode($data, true);
        $hf = (new HealthFacilityLoader($hfData))->load();
      }
    }

    return $hf;
  }

  /**
   * Update the algorithm and the version from medalc if necessary
   *
   * @param int $versionId
   * @return Version
   */
  public function updateVersion($algorithmData)
  {
    self::checkHasProperties($algorithmData, ['nodes', 'final_diagnoses', 'health_cares']);


    $algorithm = (new AlgorithmLoader($algorithmData))->load();
    $version = (new VersionLoader($algorithmData, $algorithm))->load();

    foreach ($algorithmData['nodes'] as $nodeData) {
      self::checkHasProperties($nodeData, ['type', 'answers']);
      // TODO env variable?
      if ($nodeData['type'] == 'Question') {
        $answerType = (new AnswerTypeLoader($nodeData))->load();
        $node = (new NodeLoader($nodeData, $algorithm, $answerType))->load();

        foreach ($nodeData['answers'] as $answerData) {
          $answer = (new AnswerLoader($answerData, $node))->load();
        }
      }
    }

    foreach ($algorithmData['final_diagnoses'] as $finalDiagnosisData) {
      self::checkHasProperties($finalDiagnosisData, ['type', 'drugs', 'managements']);

      $diagnosis = (new DiagnosisLoader($finalDiagnosisData, $version))->load();

      foreach ($finalDiagnosisData['drugs'] as $drugId => $drugRefData) {
        self::checkHasProperties($drugRefData, ['duration']);
        $drugData = $algorithmData['health_cares'][$drugId];
        $drug = (new DrugLoader($drugData, $diagnosis, $drugRefData['duration']))->load();

        foreach ($drugData['formulations'] as $formulationData) {
          $formulation = (new FormulationLoader($formulationData, $drug))->load();
        }
      }

      foreach ($finalDiagnosisData['managements'] as $managementId => $managementRefData) {
        $managementData = $algorithmData['health_cares'][$managementId];
        $management = (new ManagementLoader($managementData, $diagnosis))->load();
      }
    }


    foreach ($algorithmData['health_cares'] as $drugData) {
      self::checkHasProperties($drugData, ['category']);
      if ($drugData['category'] == 'drug') {
        self::checkHasProperties($drugData, ['formulations']);
        $drug = (new DrugLoader($drugData))->load();

        foreach ($drugData['formulations'] as $formulationData) {
          $formulation = (new FormulationLoader($formulationData, $drug))->load();
        }
      }
    }

    return $version;
  }

  /**
   * Save the patient and their config file from a medical case
   *
   * @param object $caseData
   * @param PatientConfig $patientConfig
   * @return Patient
   */
  public function savePatient($caseData, $patientConfig, $version)
  {
    $patientData = $caseData['patient'];
    self::checkHasProperties($patientData, ['uid', 'consent_file']);

    // Consent file
    $consentFileName = null;
    if ($version->consent_management) {
      $consentPath = Config::get('medal.storage.consent_img_dir');
      $consentFileName = $patientData['uid'] . '_image.jpg';
      Storage::makeDirectory($consentPath);
      $consentImg = Image::make($patientData['consent_file']);
      $consentImg->save(Storage::disk('local')->path($consentPath . '/' . $consentFileName));
    }

    // Patient
    $patientLoader = new PatientLoader($patientData, $caseData['nodes'], $patientConfig, $consentFileName);
    $duplicateDataExists = Patient::where($patientLoader->getDuplicateConditions())->exists();

    $existingPatientIsTrue = false;
    if (strpos($version->study, "Dynamic") !== false) {
      $existingPatientIsTrue = Answer::where($patientLoader->getExistingPatientAnswer())->first()->label == 'Yes';
    }

    $patientLoader->flagAsDuplicate($duplicateDataExists, $existingPatientIsTrue);

    return $patientLoader->load();
  }

  /**
   * Save the case
   *
   * @param object $caseData
   * @param Version $version
   * @param Patient $patient
   * @return MedicalCase
   */
  public function saveCase($caseData, $version, $patient)
  {
    // Medical case
    $medicalCase = (new MedicalCaseLoader($caseData, $patient, $version))->load();
    $medicalCase->group_id=$patient->group_id;
    $medicalCase->save();
    // Case answers
    foreach ($caseData['nodes'] as $nodeData) {
      self::checkHasProperties($nodeData, ['id', 'answer']);
      $algoNode = Node::where('medal_c_id', $nodeData['id'])->first();

      if ($algoNode) { // Only responses to questions are stored (QS for example aren't)
        $algoNodeAnswer = Answer::where('medal_c_id', $nodeData['answer'])->first();
        $medicalCaseAnswer = (new MedicalCaseAnswerLoader($nodeData, $medicalCase, $algoNode, $algoNodeAnswer))->load();
      }
    }

    // Diagnoses
    $diagnosesData = $caseData['diagnosis'];
    self::checkHasProperties($diagnosesData, ['agreed', 'additional', 'excluded', 'refused', 'custom']);
    $this->saveDiagnoses($diagnosesData['agreed'], $medicalCase, false, false, true);
    $this->saveDiagnoses($diagnosesData['additional'], $medicalCase, true, false, true);
    $this->saveDiagnoses($diagnosesData['excluded'], $medicalCase, false, true, false);
    $this->saveDiagnoses($diagnosesData['refused'], $medicalCase, false, false, false);

    foreach ($diagnosesData['custom'] as $customDiagnosisData) {
      self::checkHasProperties($customDiagnosisData, ['drugs']);
      $customDiagnosis = (new CustomDiagnosisLoader($customDiagnosisData, $medicalCase))->load();

      foreach ($customDiagnosisData['drugs'] as $customDrugData) {
        $customDrug = (new CustomDrugLoader($customDrugData, $customDiagnosis))->load();
      }
    }

    return $medicalCase;
  }

  /**
   * Save diagnostics from a case
   *
   * @param object $diagnosesData
   * @param MedicalCase $medicalCase
   * @param boolean $additional
   * @return void
   */
  protected function saveDiagnoses($diagnosesData, $medicalCase, $additional, $isExcluded, $isAgreed)
  {

    foreach ($diagnosesData as $diagnosisRefData) {

      $diagnosisId = $diagnosisRefData['id'] ?? $diagnosisRefData;

      $diagnosis = Diagnosis::where('medal_c_id', $diagnosisId)->first();
      $diagnosisRef = (new DiagnosisReferenceLoader($diagnosisRefData, $medicalCase, $diagnosis, $additional, $isExcluded, $isAgreed))->load();

      if ($isAgreed && !$isExcluded) {
        self::checkHasProperties($diagnosisRefData, ['drugs']);
        $drugRefsData = $diagnosisRefData['drugs'];

        foreach ($drugRefsData['agreed'] as $drugId => $drugRefData) {
          self::checkHasProperties($drugRefData, ['formulation_id']);
          $drug = Drug::where('medal_c_id', $drugId)->first();
          $formulation = Formulation::where('medal_c_id', $drugRefData['formulation_id'])->first();
          $drugRef = (new DrugReferenceLoader($drugRefData, $diagnosisRef, $drug, $formulation, true, false))->load();
        }

        foreach ($drugRefsData['additional'] as $drugId => $drugRefData) {
          self::checkHasProperties($drugRefData, ['formulation_id']);
          $drug = Drug::where('medal_c_id', $drugId)->first();
          $formulation = Formulation::where('medal_c_id', $drugRefData['formulation_id'])->first();
          $drugRef = (new DrugReferenceLoader($drugRefData, $diagnosisRef, $drug, $formulation, true, true))->load();
        }

        foreach ($drugRefsData['refused'] as $drugId) {
          $drugRefData = null;
          $drug = Drug::where('medal_c_id', $drugId)->first();
          $drugRef = (new DrugReferenceLoader($drugRefData, $diagnosisRef, $drug, null, false, false))->load();
        }

        foreach ($diagnosisRefData['managements'] as $managementId) {
          $management = Management::where('medal_c_id', $managementId)->first();
          $managementRef = (new ManagementReferenceLoader($managementId, $diagnosisRef, $management))->load();
        }
      }
    }
  }

  private static function checkHasProperties($data, $properties)
  {
    foreach ($properties as $property) {
      if (!array_key_exists($property, $data)) {
        throw new InvalidArgumentException("Missing property '$property'");
      }
    }
  }
}
