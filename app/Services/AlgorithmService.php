<?php


namespace App\Services;

use Exception;
use App\Device;
use App\Algorithm;
use App\VersionJson;
use App\Services\Http;
use App\HealthFacility;
use App\HealthFacilityAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class AlgorithmService {

    /**
     * Fetches the algorithms metadata from medal-creator and stores the data in the database for potential later use
     */
    public function getAlgorithmsMetadata(){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint');
        $response = Http::get($url,[]);
        return json_decode($response['content']);
    }

    public function getVersionsMetadata($algorithmCreatorID){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') .  "/" .  $algorithmCreatorID . "/versions";
        $response = Http::get($url,[]);
        return json_decode($response['content']);
    }

    public function assignVersionToHealthFacility(HealthFacility $healthFacility, $chosenAlgorithmID, $versionID) {
        /*
         * Récupération de l'algorithm
         *  - envoi du json_version pour optenir la dernier version.
         *  - Si on est à jours on reçoit un 204 si non un 200 avec le json
         *  - Si on a pas d'algorithm en DB alors on passe -1 pour obtenir la dernier version
         */
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.versions_endpoint') .
               "/" . $versionID;
        $version = Http::get($url, ["json_version" => $healthFacility->medal_r_json_version ?? -1]);
        $version = json_decode($version['content'], true);

        /*
         * Récupération du emergency content lié à l'algorithm
         *  - envoi du emergency_content_version pour optenir la dernier version.
         *  - Si on est à jours on reçoit un 204 si non un 200 avec le json
         *  - Si on a pas de emergency_content en DB alors on passe -1 pour obtenir la dernier version
         */
        $urlAlgorithm = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') .
            "/" . $chosenAlgorithmID . "/emergency_content";
        $versionJson = VersionJson::where('health_facility_id', $healthFacility->id)->first();
        $emergencyContent = Http::post($urlAlgorithm, [],
            ["emergency_content_version" => -1]);
        $emergencyContent = json_decode($emergencyContent['content'],true);

        $this->assignVersion($healthFacility, $version);
        $this->assignEmergencyContent($healthFacility, $emergencyContent);
        $this->updateAccesses($healthFacility, $chosenAlgorithmID, $version);
    }

    private function assignVersion(HealthFacility $healthFacility, $version){
        $versionJson = VersionJson::where('health_facility_id',$healthFacility->id)->first();
        if ($versionJson == null){
            $this->addVersion($healthFacility, $version);
        }else{
            $this->updateVersion($versionJson, $version);
        }
    }

    private function assignEmergencyContent(HealthFacility $healthFacility, $emergencyContent) {
        $versionJson = VersionJson::where('health_facility_id',$healthFacility->id)->first();
        if ($versionJson == null){
            $this->addVersionEmergencyContent($healthFacility, $emergencyContent);
        }else{
            $this->updateVersionEmergencyContent($versionJson, $emergencyContent);
        }
    }

    public function updateVersion(VersionJson $versionJson, $version){
        $versionJson->json = json_encode($version["medal_r_json"]);
        $versionJson->save();
    }

    public function updateVersionEmergencyContent(VersionJson $versionJson, $emergencyContent){
        $versionJson->emergency_content = json_encode($emergencyContent);
        $versionJson->emergency_content_version = $emergencyContent["emergency_content_version"];
        $versionJson->save();
    }

    public function addVersionEmergencyContent(HealthFacility $healthFacility, $emergencyContent){
        $versionJson = new VersionJson();
        $versionJson->emergency_content = json_encode($emergencyContent);
        $versionJson->emergency_content_version = $emergencyContent["emergency_content_version"];
        $versionJson->save();
        $healthFacility->version_json_id = $versionJson->id;
        $healthFacility->save();
    }

    public function addVersion(HealthFacility $healthFacility, $version){
        $versionJson = new VersionJson();
        $versionJson->health_facility_id = $healthFacility->id;
        $versionJson->json = json_encode($version["medal_r_json"]);
        $versionJson->save();
        $healthFacility->version_json_id = $versionJson->id;
        $healthFacility->save();
    }


    public function getArchivedAccesses(HealthFacility $healthFacility){
        return HealthFacilityAccess::where('health_facility_id',$healthFacility->id)->
                                     where('access',false)->get();
    }

    public function getCurrentAccess(HealthFacility $healthFacility){
        return HealthFacilityAccess::where('health_facility_id',$healthFacility->id)->
                                     where('access',true)->first();
    }

    public function updateHealthFacilityAccessJsonVersion(HealthFacilityAccess $facilityAccess, $json_version) {
        $facilityAccess->medal_r_json_version = $json_version;
        $facilityAccess->save();
    }

    private function updateAccesses(HealthFacility $healthFacility, $chosenAlgorithmID, $version){
        $access = HealthFacilityAccess::where('health_facility_id',$healthFacility->id)->
                                        where('access',true)->
                                        first();
        if ($access != null){
            $this->archiveAccess($access);
        }
        $this->newAccess($healthFacility, $chosenAlgorithmID, $version);
    }

    private function newAccess(HealthFacility $healthFacility, $chosenAlgorithmID, $version){
        $access = new HealthFacilityAccess();
        $access->access = true;
        $access->creator_version_id = $version['id'];
        $access->version_name = $version['name'];
        $access->medal_r_json_version = $version['medal_r_json_version'];
        $access->is_arm_control = $version['is_arm_control'];
        $access->health_facility_id = $healthFacility->id;
        $access->medal_c_algorithm_id = $chosenAlgorithmID;
        $access->save();
    }

    private function archiveAccess(HealthFacilityAccess $access){
        $access->access= false;
        $access->end_date = now();
        $access->save();
    }

    public function getAlgorithmJsonForDevice(Device $device){
        $healthFacility = HealthFacility::where('id',$device->health_facility_id)->first();
        if ($healthFacility == null){
            throw new Exception("Device is not assigned to any Health Facility");
        }
        if ($healthFacility->versionJson == null){
            throw new Exception("No Version is assigned to the associated Health Facility");
        }
        return json_decode($healthFacility->versionJson->json);
    }

    public function getAlgorithmEmergencyContentJsonForDevice(Device $device){
        $healthFacility = HealthFacility::where('id',$device->health_facility_id)->first();
        if ($healthFacility == null){
            throw new Exception("Device is not assigned to any Health Facility");
        }
        if ($healthFacility->versionJson == null){
            throw new Exception("No Version is assigned to the associated Health Facility");
        }
        return json_decode($healthFacility->versionJson->emergency_content);
    }

}
