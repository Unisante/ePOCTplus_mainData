<?php


namespace App\Services;


use App\CustomDiagnosis;
use App\CustomDrug;
use App\DiagnosisReference;
use App\Exceptions\RedCapApiServiceException;
use App\Followup;
use App\ManagementReference;
use App\MedicalCaseAnswer;
use App\PatientFollowUp;
use App\MedicalCase;
use App\Patient;
use App\RedCapProject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use IU\PHPCap\PhpCapException;

class RedCapApiService
{
  /**
   * @var RedCapProject
   */
  protected $projectPatient;

  /**
   * @var RedCapProject
   */
  protected $projectFollowup;


  /**
   * @var array
   */
  protected $projectsCache = [];


  /**
   * @var array
   */
  protected $surveysCache = [];


  /**
   * RedCapContactApiService constructor.
   * @param RedCapProject $project
   * @throws PhpCapException
   */
  public function __construct()
  {

    $this->projectFollowup = $this->getRedCapProject(
      Config::get('redcap.identifiers.api_url_followup'),
      Config::get('redcap.identifiers.api_token_followup')
    );

     $this->projectMedicalCase = $this->getRedCapProject(
       Config::get('redcap.identifiers.api_url_medical_case'),
       Config::get('redcap.identifiers.api_token_medical_case')
     );

  }


  /**
   * @param string $url
   * @param string $token
   * @return RedCapProject
   * @throws RedCapApiServiceException
   */
  public function getRedCapProject(string $url, string $token): RedCapProject
  {
    $cacheKey = $this->buildProjectCacheKey($url, $token);
    if (isset($this->projectsCache[$cacheKey])) {
      return $this->projectsCache[$cacheKey];
    }

    try {
      return $this->projectsCache[$cacheKey] = new RedCapProject($url, $token);
    } catch (PhpCapException $e) {
      throw new RedCapApiServiceException('Was unable to get RedCap project', 0, $e);
    }
  }


  /**
   * @param string $url
   * @param string $token
   * @return string
   */
  protected function buildProjectCacheKey(string $url, string $token): string
  {
    return "{$url}_{$token}";
  }

  /**
   * @param Collection<Patient> $patients
   * @throws RedCapApiServiceException
   */
  public function exportPatient(Collection $patients): array
  {
    // check if we still have patient to push
    if (count($patients) !== 0) {
      /** @var PatientFollowUp $patient*/
      // create redcap record for every patients
      foreach ($patients as $patient) {
        // this is the mapping between redcap field (define in config) and patient model
        // has to be update everytime we add a new field
        $datas[$patient->getPatientId()] = [
          Config::get('redcap.identifiers.patient.dyn_pat_study_id_patient') => $patient->getLocalPatientId(),
          Config::get('redcap.identifiers.patient.dyn_pat_first_name') => $patient->getFirstname(),
          Config::get('redcap.identifiers.patient.dyn_pat_last_name') => $patient->getLastName(),
          Config::get('redcap.identifiers.patient.dyn_pat_dob') => $patient->getBirthDay(),
          Config::get('redcap.identifiers.patient.dyn_pat_village') => $patient->getVillage(),
          Config::get('redcap.identifiers.patient.dyn_pat_sex') => $patient->getGender(),
          Config::get('redcap.identifiers.patient.dyn_pat_first_name_caregiver') => $patient->getCareGiverFirstName(),
          Config::get('redcap.identifiers.patient.dyn_pat_last_name_caregiver') => $patient->getCareGiverLastName(),
          Config::get('redcap.identifiers.patient.dyn_pat_relationship_child') => $patient->getChildrelation(),
          Config::get('redcap.identifiers.patient.dyn_pat_phone_caregiver') => $patient->getPhoneNumber(),
          Config::get('redcap.identifiers.patient.dyn_pat_phone_caregiver_2') => $patient->getOtherPhoneNumber()

        ];
      }

      // call redcap API
      try {
        return $this->projectPatient->importRecords(json_encode($datas), null, null, null, null, 'ids');
      } catch (PhpCapException $e) {
        // unique field redcap error
        if ($e->getCode() === 7) {
          throw new RedCapApiServiceException("unique field error", 7, $e);
        }
        throw new RedCapApiServiceException($e);
        throw new RedCapApiServiceException("Failed to create participant {}", 0, $e);
      }
    } else {
      return [];
    }
  }


  /**
   * @param MedicalCase $medicalCases
   * @throws RedCapApiServiceException
  */
  public function exportMedicalCase(MedicalCase $medicalCase)
  {
    $data = [];
    // TODO : use .env variable;
    $medalDataID = 'MDTZ-';

    try {
      $this->projectMedicalCase->importRecords([
        $medicalCase->id => [
          "record_id" => $medicalCase->local_medical_case_id,
          Config::get('redcap.identifiers.medical_case.patient_id') => $medicalCase->patient->local_patient_id,
          Config::get('redcap.identifiers.medical_case.datetime_consultation') => $medicalCase->consultation_date,
          Config::get('redcap.identifiers.medical_case.datetime_closedAt') => $medicalCase->closedAt,
          Config::get('redcap.identifiers.medical_case.complete') => ($medicalCase->force_close) ? 0 : 2,
          Config::get('redcap.identifiers.medical_case.arm') => ($medicalCase->version->algorithm->is_arm_control) ? "0" : "1",
          Config::get('redcap.identifiers.medical_case.hf_id') => $medicalCase->patient->group_id,
        ]
      ]);
      Log::info('--> Start export MC : '. $medicalCase->local_medical_case_id);
      Log::info('----> Baseline processed');

      // Variables
      /** @var MedicalCaseAnswer $medicalCaseAnswer */
      $instanceNumber = 1;
      foreach ($medicalCase->medical_case_answers as $medicalCaseAnswer) {
        // We don't push specifique type of variable
        if ($medicalCaseAnswer->node->category == "background_calculation" && $medicalCaseAnswer->node->display_format != 'Reference') {
          continue;
        }

        // Questions that were not asked
        if($medicalCaseAnswer->value == '' and $medicalCaseAnswer->answer_id === null) {continue;}

        $records[] = [
          'record_id' => $medicalCase->local_medical_case_id,
          'redcap_repeat_instrument' => 'variables',
          'redcap_repeat_instance' => $instanceNumber++,
          Config::get('redcap.identifiers.medical_case.dyn_mc_medalc_question_id') => $medicalCaseAnswer->node->medal_c_id,
          Config::get('redcap.identifiers.medical_case.dyn_mc_medalc_question_label') => $medicalCaseAnswer->node->label,
          Config::get('redcap.identifiers.medical_case.dyn_mc_medalc_answer_id') => ($medicalCaseAnswer->answer) ? $medicalCaseAnswer->answer->medal_c_id : null,
          Config::get('redcap.identifiers.medical_case.dyn_mc_medalc_answer_value') => ($medicalCaseAnswer->value == null) ? $medicalCaseAnswer->answer->label : $medicalCaseAnswer->value,
          Config::get('redcap.identifiers.medical_case.variables_complete') => 2,
        ];
        $this->projectMedicalCase->importRecords($records);
      }
      Log::info('----> Variables processed');

      // Activities
      /** @var MedicalCaseAnswer $medicalCaseAnswer */
      $instanceNumber = 1;
      foreach ($medicalCase->activities as $activity) {
        $records[] = [
          'record_id' => $medicalCase->local_medical_case_id,
          'redcap_repeat_instrument' => 'activities',
          'redcap_repeat_instance' => $instanceNumber++,
          'dyn_mc_medal_data_step' => $activity->step,
          'dyn_mc_medal_data_clinician' => $activity->clinician,
          'dyn_mc_medal_data_mac_add' => $activity->mac_address,
          'activities_complete' => 2,
        ];
        $this->projectMedicalCase->importRecords($records);
      }
      Log::info('----> Activities processed');

      // Diagnoses
      /** @var DiagnosisReference $diagnose */
      $instanceNumber = 1;
      foreach ($medicalCase->diagnosesReferences as $diagnose) {
        if ($diagnose->excluded) {continue;};

        if ($diagnose->agreed) {
          $records[] = [
            'record_id' => $medicalCase->local_medical_case_id,
            'redcap_repeat_instrument' => 'diagnoses',
            'redcap_repeat_instance' => $instanceNumber++,
            'dyn_mc_medalc_diag_id' => $diagnose->diagnoses->medal_c_id,
            'dyn_mc_medal_data_diag_id' => $medalDataID . $diagnose->id,
            'dyn_mc_medal_data_diag_additional' => ($diagnose->additional) ? 'true' : 'false',
            'dyn_mc_medalc_diag_label' => $diagnose->diagnoses->label,
            'diagnoses_complete' => 2,
          ];
        } else {
          $records[] = [
            'record_id' => $medicalCase->local_medical_case_id,
            'redcap_repeat_instrument' => 'diagnoses_refused',
            'redcap_repeat_instance' => $diagnose->id,
            'dyn_mc_medalc_diag_refused_id' => $diagnose->diagnoses->medal_c_id,
            'diagnoses_refused_complete' => 2,
          ];
        }

        $this->projectMedicalCase->importRecords($records);
      }
      Log::info('----> Diagnoses processed');

      // Custom Diagnoses
      /** @var CustomDiagnosis $diagnose */
      $instanceNumber = 1;
      foreach ($medicalCase->customDiagnoses as $customDiagnose) {
        $records[] = [
          'record_id' => $medicalCase->local_medical_case_id,
          'redcap_repeat_instrument' => 'custom_diagnoses',
          'redcap_repeat_instance' => $instanceNumber++,
          'dyn_mc_medal_data_custom_diag_label' => $customDiagnose->label,
          'dyn_mc_medal_data_custom_diag_drugs' => $customDiagnose->drugs,
          'dyn_mc_medal_data_custom_diag_id' => $medalDataID . $customDiagnose->id,
          'custom_diagnoses_complete' => 2,

        ];
        $this->projectMedicalCase->importRecords($records);
      }
      Log::info('----> Custom Diagnoses processed');

      // Drugs
      /** @var DiagnosisReference $diagnose */
      $instanceNumber = 1;
      foreach ($medicalCase->diagnosesReferences as $diagnose) {
        if ($diagnose->excluded) {continue;};

        if ($diagnose->agreed) {
          foreach ($diagnose->drugReferences as $drug) {
            $records[] = [
              'record_id' => $medicalCase->local_medical_case_id,
              'redcap_repeat_instrument' => 'drugs',
              'redcap_repeat_instance' => $instanceNumber++,
              'dyn_mc_medalc_drug_id' => $drug->drugs->medal_c_id,
              'dyn_mc_medalc_drug_type' => $drug->type,
              'dyn_mc_medalc_drug_label' => $drug->drugs->label,
              'dyn_mc_medalc_drug_description' => $drug->drugs->description,
              'dyn_mc_medalc_drug_is_anti_malarial' => ($drug->drugs->is_anti_malarial) ? "true" : "false",
              'dyn_mc_medalc_drug_is_anti_biotic' => ($drug->drugs->is_antibiotic) ? "true" : "false",
              'dyn_mc_medalc_drug_duration' => $drug->drugs->duration,
              'dyn_mc_medal_data_drug_effective_duration' => $drug->duration,
              'dyn_mc_medal_data_drug_additional' => ($drug->addtional) ? "true" : "false",

              'dyn_mc_medal_data_drug_diag_id' => $medalDataID.$diagnose->id,
              'drugs_complete' => 2,
            ];
          }
          $this->projectMedicalCase->importRecords($records);
        };
      }
      Log::info('----> drugs processed');

      // Custom Drugs
      /** @var CustomDiagnosis $customDiagnose */
      $instanceNumber = 1;
      foreach ($medicalCase->customDiagnoses as $customDiagnose) {
        /** @var CustomDrug $customDrug */
        foreach ($customDiagnose->customDrugs as $customDrug) {
          $records[] = [
            'record_id' => $medicalCase->local_medical_case_id,
            'redcap_repeat_instrument' => 'custom_drugs',
            'redcap_repeat_instance' => $instanceNumber++,
            'dyn_mc_medal_data_custom_drugs_name' => $customDrug->name,
            'dyn_mc_medal_data_custom_drugs_duration' => $customDrug->duration,
            'dyn_mc_medal_data_custom_drugs_custom_diag_id' => $medalDataID . $customDiagnose->id,
            'custom_drugs_complete' => 2,

          ];
          $this->projectMedicalCase->importRecords($records);
        }
      }
      Log::info('----> Custom Drugs processed');

      // Managements
      /** @var DiagnosisReference $diagnose */
      $instanceNumber = 1;
      foreach ($medicalCase->diagnosesReferences as $diagnose) {
        if ($diagnose->excluded) {continue;};

        if ($diagnose->agreed) {
          /** @var ManagementReference $management */
          foreach ($diagnose->managementReferences as $management) {
              $records[] = [
                'record_id' => $medicalCase->local_medical_case_id,
                'redcap_repeat_instrument' => 'managements',
                'redcap_repeat_instance' => $instanceNumber++,
                'dyn_mc_medalc_management_id' => $management->managements->medal_c_id,
                'dyn_mc_medalc_management_type' => $management->managements->type,
                'dyn_mc_medalc_management_label' => $management->managements->label,
                'dyn_mc_medalc_management_description' => $management->managements->description,
                'dyn_mc_medal_data_management_diag_id' => $medalDataID.$diagnose->id,
                'managements_complete' => 2,
              ];
            $this->projectMedicalCase->importRecords($records);
          }
        }
      }
      Log::info('----> Management processed');

    } catch (PhpCapException $e) {
      if ($e->getCode() === 7) {
        throw new RedCapApiServiceException("unique field error", 7, $e);
      }
      throw new RedCapApiServiceException("Failed to export Medical case " . $medicalCase->local_medical_case_id , 0, $e);
    }

  }


  /**
   * @param Collection<\stdClass> $followups
   * @throws RedCapApiServiceException
   */
  public function exportFollowup(Collection $followups): array
  {

    if (count($followups) !== 0) {
      /** @var Followup $followup*/
      foreach ($followups as $followup) {
        // this is the mapping between redcap field (define in config) and followup model
        // has to be update everytime we add a new field
        $datas[$followup->getConsultationId()] = [
          'redcap_event_name' => Config::get('redcap.identifiers.followup.redcap_event_name'),
          Config::get('redcap.identifiers.followup.dyn_fup_study_id_consultation') => $followup->getConsultationId(),
          Config::get('redcap.identifiers.followup.dyn_fup_study_id_patient') => $followup->getPatientId(),
          Config::get('redcap.identifiers.followup.dyn_fup_id_health_facility') => $followup->getFacilityId(),
          Config::get('redcap.identifiers.followup.dyn_fup_date_time_consultation') => $followup->getConsultationDate(),
          Config::get('redcap.identifiers.followup.dyn_fup_group') => $followup->getGroupId(),

          Config::get('redcap.identifiers.patient.dyn_pat_first_name_caregiver') => $followup->getCareGiverFirstName(),
          Config::get('redcap.identifiers.patient.dyn_pat_last_name_caregiver') => $followup->getCareGiverLastName(),
          Config::get('redcap.identifiers.patient.dyn_pat_relationship_child') => $followup->getChildrelation(),
          Config::get('redcap.identifiers.patient.dyn_pat_phone_caregiver') => $followup->getPhoneNumber(),
          Config::get('redcap.identifiers.patient.dyn_pat_phone_caregiver_2') => $followup->getOtherPhoneNumber(),

        ];
      }

      // call redcap API
      try {
        return $this->projectFollowup->importRecords($datas, null, null, null, null, 'ids');
      } catch (PhpCapException $e) {
        // unique field redcap error
        if ($e->getCode() === 7) {
          throw new RedCapApiServiceException("unique field error", 7, $e);
        }
        throw new RedCapApiServiceException("Failed to create participant {}", 0, $e);
      }
    } else {
      return [];
    }
  }

}
