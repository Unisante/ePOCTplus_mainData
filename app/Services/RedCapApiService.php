<?php


namespace App\Services;


use App\Exceptions\RedCapApiServiceException;
use App\Followup;
use App\PatientFollowUp;
use App\MedicalCase;
use App\Patient;
use App\RedCapProject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
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

    $this->projectPatient = $this->getRedCapProject(

      Config::get('redcap.identifiers.api_url_patient'),
      Config::get('redcap.identifiers.api_token_patient')
    );
    // $this->projectPersonalData = $this->getRedCapProject(
    //   Config::get('redcap.identifiers.api_url_personal_data'),
    //   Config::get('redcap.identifiers.api_token_personal_data')
    // );

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
