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
  public function save($caseData) {
    self::checkHasProperties($caseData, ['patient', 'nodes', 'diagnosis']);
    self::checkHasProperties($caseData['patient'], ['group_id']);
    $hf = $this->udpateHf($caseData['patient']['group_id']);

    //$versionId = $caseData['version_id'];
    $versionId = 1; // TODO should be provided with the case
    $version = $this->updateVersion($versionId);
    $patientConfig = $this->updateConfig($version);
    $patient = $this->savePatient($caseData, $patientConfig);
    $case = $this->saveCase($caseData, $version, $patient);

    return $case;
  } 

  /**
   * Update the patient config from medalc
   *
   * @param Version $version
   * @return PatientConfig
   */
  public function updateConfig($version) {
    $config = PatientConfig::where('version_id', $version->id)->first();
    if ($config) {
      return $config;
    }

    $configData = json_decode(Http::get(Config::get('medal-data.urls.creator_patient_url'), ['version_id' => $version->medal_c_id]), true);
    return (new PatientConfigLoader($configData, $version))->load();
  }

  /**
   * Update the health facility from medalc if necessary
   *
   * @param int $groupId
   * @return HealthFacility
   */
  public function udpateHf($groupId) {
    $hf = HealthFacility::where('group_id', $groupId)->first();
    if ($hf) {
      return $hf;
    }

    $hfData = json_decode(Http::get(Config::get('medal-data.urls.creator_health_facility_url') . $groupId), true);
    $hf = (new HealthFacilityLoader($hfData))->load();
    return $hf;
  }

  /**
   * Update the algorithm and the version from medalc if necessary
   *
   * @param int $versionId
   * @return Version
   */
  public function updateVersion($versionId) {
    $version = Version::where('medal_c_id', $versionId)->first();
    if ($version) {
      return $version;
    }

    $algorithmData = json_decode(Http::get(Config::get('medal-data.urls.creator_algorithm_url') . $versionId), true);
    self::checkHasProperties($algorithmData, ['nodes', 'diagnostics', 'health_cares']);


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
   
    foreach ($algorithmData['diagnostics'] as $diagnosisData) {
      self::checkHasProperties($diagnosisData, ['final_diagnostics']);
      foreach ($diagnosisData['final_diagnostics'] as $finalDiagnosisData) {
        self::checkHasProperties($finalDiagnosisData, ['type', 'drugs', 'managements']);

        // TODO env variable?
        if (array_key_exists('diagnostic_id',$finalDiagnosisData) && $finalDiagnosisData['type'] == 'FinalDiagnostic') {
          $diagnosis = (new DiagnosisLoader($finalDiagnosisData, $version))->load();

          foreach ($finalDiagnosisData['drugs'] as $drugId => $drugRefData) {
            $drugData = $algorithmData['health_cares'][$drugId];
            $drug = (new DrugLoader($drugData, $diagnosis))->load();

            foreach ($drugData['formulations'] as $formulationData) {
              $formulation = (new FormulationLoader($formulationData, $drug))->load();
            }
          }

          foreach ($finalDiagnosisData['managements'] as $managementId => $managementRefData) {
            $managementData = $algorithmData['health_cares'][$managementId];
            $management = (new ManagementLoader($managementData, $diagnosis))->load();
          }
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
  public function savePatient($caseData, $patientConfig) {
    // Consent file
    $patientData = $caseData['patient'];
    self::checkHasProperties($patientData, ['uid', 'consent_file']);
    $consentPath = Config::get('medal-data.storage.consent_img_dir');
    $consentFileName = $patientData['uid'] . '_image.jpg';
    Storage::makeDirectory($consentPath);
    $consentImg = Image::make($patientData['consent_file']);
    $consentImg->save(Storage::disk('local')->path($consentPath . '/' . $consentFileName));

    // Patient
    $patientLoader = new PatientLoader($patientData, $caseData['nodes'], $patientConfig, $consentFileName);
    $duplicateDataExists = Patient::where($patientLoader->getDuplicateConditions())->exists();
    // TODO if(strpos(env("STUDY_ID"), "Dynamic")!== false) -> see original code
    $existingPatientIsTrue = Answer::where($patientLoader->getExistingPatientAnswer())->first()->label == 'Yes';
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
  public function saveCase($caseData, $version, $patient) {
    // Medical case
    $medicalCase = (new MedicalCaseLoader($caseData, $patient, $version))->load();

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
    $this->saveDiagnoses($diagnosesData['agreed'], $medicalCase, true, false, true);
    $this->saveDiagnoses($diagnosesData['additional'], $medicalCase, false, false, true);
    $this->saveDiagnoses($diagnosesData['excluded'], $medicalCase, false, true, false);
    $this->saveDiagnoses($diagnosesData['refused'], $medicalCase, true, false, false);
    
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
   * @param boolean $isProposed
   * @return void
   */
  protected function saveDiagnoses($diagnosesData, $medicalCase, $isProposed, $isExcluded, $isAgreed) {
    
    foreach ($diagnosesData as $diagnosisRefData) {

      $diagnosisId = $diagnosisRefData['id'] ?? $diagnosisRefData;

      $diagnosis = Diagnosis::where('medal_c_id', $diagnosisId)->first();
      $diagnosisRef = (new DiagnosisReferenceLoader($diagnosisRefData, $medicalCase, $diagnosis, $isProposed, $isExcluded, $isAgreed))->load();
      
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

  private static function checkHasProperties($data, $properties) {
    foreach ($properties as $property) {
      if (!array_key_exists($property, $data)) {
        throw new InvalidArgumentException();
      }
    }
  }
}
