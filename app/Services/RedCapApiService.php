<?php


namespace App\Services;


use App\Exceptions\RedCapApiServiceException;
use App\Followup;
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

    $this->projectPersonalData = $this->getRedCapProject(
      Config::get('redcap.identifiers.api_url_personal_data'),
      Config::get('redcap.identifiers.api_token_personal_data')
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

    $filteredPatients = $patients->filter(function (Patient $patient) {
      return !$patient->isRedcapFlagged();
    });

    // check if we still have patient to push
    if (count($filteredPatients) !== 0) {
      // create redcap record for every patients
      foreach ($filteredPatients as $patient) {
        // this is the mapping between redcap field (define in config) and patient model
        // has to be update everytime we add a new fiedl
        $datas[$patient['id']] = [
          Config::get('redcap.identifiers.patient.id') => $patient->id,
          Config::get('redcap.identifiers.patient.firstName') => $patient->first_name,
          Config::get('redcap.identifiers.patient.lastName') => $patient->last_name,
        ];
      }

      // call redcap API
      try {
        return $this->projectPersonalData->importRecords($datas, null, null, null, null, 'ids');
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


  /**
   * @param Collection<Followup> $followups
   * @throws RedCapApiServiceException
   */
  public function exportFollowup(Collection $followups): array
  {
    // We keep followup only if patient exist in redcap and medicale case don't
    $filteredFollowups = $followups->filter(function (Followup $followup) {
      return (!$followup->isRedcapFlagForMedicalCase() and $followup->isRedcapFlagForPatient());
    });

    if (count($filteredFollowups) !== 0) {
      foreach ($filteredFollowups as $followup) {
        // this is the mapping between redcap field (define in config) and followup model
        // has to be update everytime we add a new fiedl
        $datas[$followup->getMedicalCase()->id] = [
          Config::get('redcap.identifiers.followup.id') => $followup->getMedicalCase()->id,
          Config::get('redcap.identifiers.followup.patient_id') => $followup->getPatient()->id,
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
