<?php


namespace App\Services;


use App\Exceptions\RedCapApiServiceException;
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
  protected $projectPersonalData;

  /**
   * @var RedCapProject
   */
  protected $projectStudyData;

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

    $this->projectPersonalData = $this->getRedCapProject(
      Config::get('redcap.identifiers.api_url_personal_data'),
      Config::get('redcap.identifiers.api_token_personal_data')
    );

    $this->projectStudyData = $this->getRedCapProject(
      Config::get('redcap.identifiers.api_url_study_data'),
      Config::get('redcap.identifiers.api_token_study_data')
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
  public function exportPatient(Collection $patients): void
  {
    // first, we need to check if the patients id already exist in redcap
    // get all id and filtre the patients collection by id
    $recordsID = $this->projectPersonalData->exportRecords(null, null, null, ['record_id']);
    $formatedRecordsID = [];
    foreach ($recordsID as $key => $value) {
      array_push($formatedRecordsID, $value['record_id']);
    }
    $patients = array_filter($patients->toArray(), function($value) use ($formatedRecordsID) {
      return !in_array($value['id'], $formatedRecordsID);
    });

    // check if we still have patient to push
    if (count($patients) !== 0) {
      // create redcap record for every patients
      foreach ($patients as $patient) {
        // this is the mapping between redcap field (define in config) and patient model
        // has to be update everytime we add a new fiedl
        $datas[$patient['id']] = [
          Config::get('redcap.identifiers.patient.id') => $patient['id'],
          Config::get('redcap.identifiers.patient.firstName') => $patient['first_name'],
          Config::get('redcap.identifiers.patient.lastName') => $patient['last_name'],
        ];
      }

      // call redcap API
      try {
        $this->projectPersonalData->importRecords($datas);
      } catch (PhpCapException $e) {
        // unique field redcap error
        if ($e->getCode() === 7) {
          throw new RedCapApiServiceException("unique field error", 7, $e);
        }
        throw new RedCapApiServiceException("Failed to create participant {}", 0, $e);
      }
    } else {
      // Todo : define what to do if all records already exist. Maybe nothing or define a return code ?
    }
  }

  /**
   * @param Collection<MedicalCase> $medicalCases
   * @throws RedCapApiServiceException
   */
  public function exportMedicalCase(Collection $medicalCases): void
  {
    // first, we need to check if the patients id already exist in redcap
    // get all id and filtre the patients collection by id
    $recordsID = $this->projectPersonalData->exportRecords(null, null, null, ['record_id']);
    $formatedRecordsID = [];
    foreach ($recordsID as $key => $value) {
      array_push($formatedRecordsID, $value['record_id']);
    }
    $medicalCases = array_filter($medicalCases->toArray(), function($value) use ($formatedRecordsID) {
      return !in_array($value['id'], $formatedRecordsID);
    });

    // check if we still have patient to push
    if (count($medicalCases) !== 0) {
      // create redcap record for every patients
      foreach ($medicalCases as $medicalCase) {
        // this is the mapping between redcap field (define in config) and MedicalCase model
        // has to be update everytime we add a new field
        $datas[$medicalCase['id']] = [
          Config::get('redcap.identifiers.medicalCase.id') => $medicalCase['id'],
        ];
      }

      // call redcap API
      try {
        $this->projectPersonalData->importRecords($datas);
      } catch (PhpCapException $e) {
        // unique field redcap error
        if ($e->getCode() === 7) {
          throw new RedCapApiServiceException("unique field error", 7, $e);
        }
        throw new RedCapApiServiceException("Failed to create participant {}", 0, $e);
      }
    } else {
      // Todo : define what to do if all records already exist. Maybe nothing or define a return code ?
    }
  }

}
