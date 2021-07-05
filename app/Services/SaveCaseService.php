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
   * Undocumented function
   *
   * @param object $caseData
   */
  public function __construct($caseData)
  {
    $this->caseData = $caseData;

    $versionId = $this->caseData['version_id'];

    $algorithmData = json_decode(Http::get(env('MEDALC_VERSION_URL') . $versionId), true);
    $configData = json_decode(Http::get(env('MEDALC_CONFIG_URL'), ['version_id' => $versionId]), true);
    
    $this->algorithm = (new AlgorithmLoader($algorithmData))->load();
    $this->version = (new VersionLoader($algorithmData, $this->algorithm))->load();
    $this->patientConfig = (new PatientConfigLoader($configData, $this->version))->load();
    
    if (HealthFacility::where('group_id', $caseData['patient']['group_id'])->doesntExist()) {
      $hfData = json_decode(Http::get(env('MEDALC_HF_URL') . $caseData['patient']['group_id']), true);
      (new HealthFacilityLoader($hfData))->load();
    }
    
    $this->updateAlgorithmElements($algorithmData);
  }

  public function updateAlgorithmElements($algorithmData) {
    foreach ($algorithmData['nodes'] as $nodeData) {
      // TODO env variable?
      if ($nodeData['type'] == 'Question') {
        $answerType = (new AnswerTypeLoader($nodeData))->load();
        $node = (new NodeLoader($nodeData, $this->algorithm, $answerType))->load();

        foreach ($nodeData['answers'] as $answerData)
        $answer = (new AnswerLoader($answerData, $node))->load();
      }
    }

    foreach ($algorithmData['diagnostics'] as $diagnosisData) {
      foreach ($diagnosisData['final_diagnostics'] as $finalDiagnosisData) {
        // TODO env variable?
        if (array_key_exists('diagnostic_id',$finalDiagnosisData) && $finalDiagnosisData['type'] == 'FinalDiagnostic') {
          $diagnosis = (new DiagnosisLoader($finalDiagnosisData, $this->version))->load();

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

    // TODO health facility ?
  }

  public function saveCase() {
    // Consent file
    $patientData = $this->caseData['patient'];
    $consentPath = env('CONSENT_IMG_DIR');
    $consentFileName = $patientData['uid'] . '_image.jpg';
    Storage::makeDirectory($consentPath);
    $consentImg = Image::make($patientData['consent_file']);
    $consentImg->save(Storage::disk('local')->path($consentPath . '/' . $consentFileName));

    // Patient
    $patientLoader = new PatientLoader($patientData, $this->caseData['nodes'], $this->patientConfig, $consentFileName);
    $duplicateDataExists = Patient::where($patientLoader->getDuplicateConditions())->exists();
    // TODO if(strpos(env("STUDY_ID"), "Dynamic")!== false) -> see original code
    $existingPatientIsTrue = Answer::where($patientLoader->getExistingPatientAnswer())->first()->label == 'Yes';
    $patientLoader->flagAsDuplicate($duplicateDataExists, $existingPatientIsTrue);
    $patient = $patientLoader->load();

    // Medical case
    $medicalCase = (new MedicalCaseLoader($this->caseData, $patient))->load();

    // Case answers
    foreach ($this->caseData['nodes'] as $nodeData) {
      $algoNode = Node::where('medal_c_id', $nodeData['id'])->first();

      // TODO this condition makes it hard to find out about missing questions in database
      if ($algoNode) { // Only responses to questions are stored (QS for example aren't)
        $algoNodeAnswer = Answer::where('medal_c_id', $nodeData['answer'])->first();
        $medicalCaseAnswer = (new MedicalCaseAnswerLoader($nodeData, $medicalCase, $algoNode, $algoNodeAnswer))->load();
      }
    }

    // Diagnoses
    $diagnosesData = $this->caseData['diagnoses'];
    $this->saveDiagnoses($diagnosesData['proposed'], $medicalCase, true);
    $this->saveDiagnoses($diagnosesData['additional'], $medicalCase, false);

    foreach ($diagnosesData['custom'] as $customDiagnosisData) {
      $customDiagnosis = (new CustomDiagnosisLoader($customDiagnosisData, $medicalCase))->load();
    }

    foreach ($diagnosesData['additionalDrugs'] as $additionalDrugData) {
      $drug = Drug::where('medal_c_id', $additionalDrugData['id'])->first();
      $additionalDrug = (new AdditionalDrugLoader($additionalDrugData, $medicalCase, $drug, $this->version))->load();
    }
  }

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
