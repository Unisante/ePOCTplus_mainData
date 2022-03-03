<?php

namespace App\Services;

use App\Device;
use App\HealthFacility;
use App\HealthFacilityAccess;
use App\Jobs\MakeHttpRequest;
use App\Services\Http;
use App\VersionJson;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AlgorithmService
{

    /**
     * Fetches the algorithms metadata from medal-creator and stores the data in the database for potential later use
     */
    public function getAlgorithmsMetadata()
    {
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint');
        $response = Http::get($url, []);
        return json_decode($response['content']);
    }

    /**
     * Fetches the version metadata from medal-creator for a specific algorithm id
     */
    public function getVersionsMetadata($algorithmCreatorID)
    {
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') . "/" . $algorithmCreatorID . "/versions";
        $response = Http::get($url, []);
        return json_decode($response['content']);
    }

    public function assignVersionToHealthFacility(HealthFacility $healthFacility, $chosenAlgorithmID, $versionID)
    {
        MakeHttpRequest::dispatch($healthFacility, $chosenAlgorithmID, $versionID)->onQueue("high");
    }

    public function assignVersion(HealthFacility $healthFacility, $version)
    {
        $versionJson = VersionJson::where('health_facility_id', $healthFacility->id)->first();
        if ($versionJson == null) {
            $this->addVersion($healthFacility, $version);
            Log::info("Adding version");
        } else {
            $this->updateVersion($versionJson, $version);
            Log::info("Updating version");
        }
    }

    public function assignEmergencyContent(HealthFacility $healthFacility, $emergencyContent)
    {
        $versionJson = VersionJson::where('health_facility_id', $healthFacility->id)->first();
        if ($versionJson == null) {
            $this->addVersionEmergencyContent($healthFacility, $emergencyContent);
        } else {
            $this->updateVersionEmergencyContent($versionJson, $emergencyContent);
        }
    }

    public function updateVersion(VersionJson $versionJson, $version)
    {
        // If we are up to date, nothing to do
        if (!$version) {
            Log::info("Version is up to date");
            return;
        }

        $versionJson->json = json_encode(['medal_r_json' => $version['medal_r_json']]);
        $versionJson->save();
    }

    public function updateVersionEmergencyContent(VersionJson $versionJson, $emergencyContent)
    {
        $versionJson->emergency_content = json_encode($emergencyContent);
        $versionJson->emergency_content_version = $emergencyContent["emergency_content_version"];
        $versionJson->save();
    }

    public function addVersionEmergencyContent(HealthFacility $healthFacility, $emergencyContent)
    {
        $versionJson = new VersionJson();
        $versionJson->emergency_content = json_encode($emergencyContent);
        $versionJson->emergency_content_version = $emergencyContent["emergency_content_version"];
        $versionJson->save();
        $healthFacility->version_json_id = $versionJson->id;
        $healthFacility->save();
    }

    public function addVersion(HealthFacility $healthFacility, $version)
    {
        $versionJson = new VersionJson();
        $versionJson->health_facility_id = $healthFacility->id;
        $versionJson->json = json_encode(['medal_r_json' => $version['medal_r_json']]);
        $versionJson->save();
        $healthFacility->version_json_id = $versionJson->id;
        $healthFacility->save();
    }

    /**
     * Returns the list of previously used versions for the given $healthFacility
     */
    public function getArchivedAccesses(HealthFacility $healthFacility)
    {
        return HealthFacilityAccess::where('health_facility_id', $healthFacility->id)->where('access', false)->get();
    }

    /**
     * Returns the version currently used by the health facility $healthFacility
     */
    public function getCurrentAccess(HealthFacility $healthFacility)
    {
        return HealthFacilityAccess::where('health_facility_id', $healthFacility->id)->where('access', true)->first();
    }

    public function updateHealthFacilityAccessJsonVersion(HealthFacilityAccess $facilityAccess, $json_version)
    {
        $facilityAccess->medal_r_json_version = $json_version;
        $facilityAccess->save();
    }

    public function updateAccesses(HealthFacility $healthFacility, $chosenAlgorithmID, $version)
    {
        // If we are up to date, nothing to do
        if (!$version) {
            Log::info("Access is already up to date");
            return;
        }

        $access = HealthFacilityAccess::where('health_facility_id', $healthFacility->id)
            ->where('access', true)
            ->first();

        if ($access != null) {
            $this->archiveAccess($access);
        }

        $this->newAccess($healthFacility, $chosenAlgorithmID, $version);
    }

    private function newAccess(HealthFacility $healthFacility, $chosenAlgorithmID, $version)
    {
        $access = new HealthFacilityAccess();
        $access->access = true;
        $access->creator_version_id = $version['id'];
        $access->version_name = $version['name'];
        $access->medal_r_json_version = $version['medal_r_json_version'];
        $access->is_arm_control = $version['is_arm_control'];
        $access->health_facility_id = $healthFacility->id;
        $access->medal_c_algorithm_id = $chosenAlgorithmID;
        $access->save();
        Log::info("Successfully created Access " . $access->id);
    }

    private function archiveAccess(HealthFacilityAccess $access)
    {
        $access->access = false;
        $access->end_date = now();
        $access->save();
    }

    public function getAlgorithmJsonForDevice(Device $device)
    {
        $healthFacility = HealthFacility::where('id', $device->health_facility_id)->first();
        if ($healthFacility == null) {
            throw new Exception("Device is not assigned to any Health Facility");
        }
        if ($healthFacility->versionJson == null) {
            throw new Exception("No Version is assigned to the associated Health Facility");
        }
        $jsonVersion = $healthFacility->HealthFacilityAccess->medal_r_json_version;
        return [
            "algo" => json_decode($healthFacility->versionJson->json),
            "json_version" => $jsonVersion,
        ];
    }

    public function getAlgorithmEmergencyContentJsonForDevice(Device $device)
    {
        $healthFacility = HealthFacility::where('id', $device->health_facility_id)->first();
        if ($healthFacility == null) {
            throw new Exception("Device is not assigned to any Health Facility");
        }
        if ($healthFacility->versionJson == null) {
            throw new Exception("No Version is assigned to the associated Health Facility");
        }
        $jsonVersion = $healthFacility->versionJson->emergency_content_version;
        return [
            "emergency_content" => json_decode($healthFacility->versionJson->emergency_content),
            "json_version" => $jsonVersion,
        ];
    }
}
