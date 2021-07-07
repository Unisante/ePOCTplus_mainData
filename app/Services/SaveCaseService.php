<?php

namespace App\Services;

use App\Answer;
use App\Diagnosis;
use App\Drug;
use App\HealthFacility;
use App\Management;
use App\Node;
use App\Patient;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

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
    $versionId = $caseData['version_id'];

    $version = $this->updateVersion($versionId);
    $patientConfig = $this->updateConfig($version);
    $this->udpateHf($caseData['patient']['group_id']);

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
    if (!$hf) {
      $hfData = json_decode(Http::get(Config::get('medal-data.urls.creator_health_facility_url') . $groupId), true);
      $hf = (new HealthFacilityLoader($hfData))->load();
    }
    return $hf;
  }

  /**
   * Update the algorithm and the version from medalc if necessary
   *
   * @param int $versionId
   * @return Version
   */
  public function updateVersion($versionId) {
    $algorithmData = json_decode(Http::get(Config::get('medal-data.urls.creator_algorithm_url') . $versionId), true);
    
    $algorithm = (new AlgorithmLoader($algorithmData))->load();
    $version = (new VersionLoader($algorithmData, $algorithm))->load();
    
    foreach ($algorithmData['nodes'] as $nodeData) {
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
      foreach ($diagnosisData['final_diagnostics'] as $finalDiagnosisData) {
        // TODO env variable?
        if (array_key_exists('diagnostic_id',$finalDiagnosisData) && $finalDiagnosisData['type'] == 'FinalDiagnostic') {
          $diagnosis = (new DiagnosisLoader($finalDiagnosisData, $version))->load();

          foreach ($finalDiagnosisData['drugs'] as $drugRefData) {
            // TODO the key in 'drugs' associative array is the drug id
            $drugData = $algorithmData['health_cares'][$drugRefData['id']];
            $drug = (new DrugLoader($drugData, $diagnosis))->load();

            foreach ($drugData['formulations'] as $formulationData) {
              // TODO this wouldn't work since formulatiosn has no medal_c_id column...
              //$formulation = (new FormulationLoader($formulationData, $drug));
            }
          }

          foreach ($finalDiagnosisData['managements'] as $managementRefData) {
            // TODO the key in 'managements' associative array is the key id
            $managementData = $algorithmData['health_cares'][$managementRefData['id']];
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
    $consentPath = env('CONSENT_IMG_DIR');
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
    $medicalCase = (new MedicalCaseLoader($caseData, $patient))->load();

    // Case answers
    foreach ($caseData['nodes'] as $nodeData) {
      $algoNode = Node::where('medal_c_id', $nodeData['id'])->first();

      // TODO this condition makes it hard to find out about missing questions in database
      if ($algoNode) { // Only responses to questions are stored (QS for example aren't)
        $algoNodeAnswer = Answer::where('medal_c_id', $nodeData['answer'])->first();
        $medicalCaseAnswer = (new MedicalCaseAnswerLoader($nodeData, $medicalCase, $algoNode, $algoNodeAnswer))->load();
      }
    }

    // Diagnoses
    $diagnosesData = $caseData['diagnoses'];
    $this->saveDiagnoses($diagnosesData['proposed'], $medicalCase, true);
    $this->saveDiagnoses($diagnosesData['additional'], $medicalCase, false);

    foreach ($diagnosesData['custom'] as $customDiagnosisData) {
      $customDiagnosis = (new CustomDiagnosisLoader($customDiagnosisData, $medicalCase))->load();
    }

    foreach ($diagnosesData['additionalDrugs'] as $additionalDrugData) {
      $drug = Drug::where('medal_c_id', $additionalDrugData['id'])->first();
      $additionalDrug = (new AdditionalDrugLoader($additionalDrugData, $medicalCase, $drug, $version))->load();
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
  protected function saveDiagnoses($diagnosesData, $medicalCase, $isProposed) {
    foreach ($diagnosesData as $diagnosisRefData) {
      $diagnosis = Diagnosis::where('medal_c_id', $diagnosisRefData['id'])->first();
      $diagnosisRef = (new DiagnosisReferenceLoader($diagnosisRefData, $medicalCase, $diagnosis, $isProposed))->load();

      foreach ($diagnosisRefData['drugs'] as $drugRefData) {
        $drug = Drug::where('medal_c_id', $drugRefData['id'])->first();
        $drugRef = (new DrugReferenceLoader($drugRefData, $diagnosisRef, $drug))->load();
      }

      foreach ($diagnosisRefData['managements'] as $managementRefData) {
        $management = Management::where('medal_c_id', $managementRefData['id'])->first();
        $managementRef = (new ManagementReferenceLoader($managementRefData, $diagnosisRef, $management))->load();
      }
    }
  }
}
