<?php


namespace App\Services;

use Exception;
use App\Device;
use App\Algorithm;
use App\VersionJson;
use App\Services\Http;
use App\HealthFacility;
use App\HealthFacilityAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class AlgorithmService {

    /**
     * Fetches the algorithms metadata from medal-creator and stores the data in the database for potential later use
     */
    public function getAlgorithmsMetadata(){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint');
        $response = Http::get($url,[]);
        return json_decode($response);
    }

    public function getVersionsMetadata($algorithmCreatorID){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') .  "/" .  $algorithmCreatorID . "/versions";
        $response = Http::get($url,[]);
        return json_decode($response);
    }

    public function assignVersionToHealthFacility(HealthFacility $healthFacility, $chosenAlgorithmID, $versionID) {
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.versions_endpoint') .
               "/" . $versionID;
        $urlAlgorithm = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') .
                        "/" . $chosenAlgorithmID . "/emergency_content";

        $version = json_decode(Http::get($url, []),true);
        $emergencyContent = json_decode(Http::post($urlAlgorithm, [], ["emergency_content_version" => -1]),true);
        $requiredFields = ['medal_r_json','name','id','is_arm_control','medal_r_json_version'];
        foreach($requiredFields as $field){
            if ($version[$field] === null){
                throw new Exception("Response from creator does not contain required field: $field");
            }
        }
        $this->assignVersion($healthFacility, $version, $emergencyContent);
        $this->updateAccesses($healthFacility, $chosenAlgorithmID, $version);
    }

    public function assignVersion(HealthFacility $healthFacility, $version, $emergencyContent){
        $versionJson = VersionJson::where('health_facility_id',$healthFacility->id)->first();
        if ($versionJson == null){
            $this->addVersion($healthFacility, $version, $emergencyContent);
        }else{
            $this->updateVersion($versionJson, $version, $emergencyContent);
        }
    }


    public function updateVersion(VersionJson $versionJson, $version, $emergencyContent){
        $versionJson->json = json_encode($version["medal_r_json"]);
        $versionJson->emergency_content = json_encode($emergencyContent);
        $versionJson->save();
    }

    public function addVersion(HealthFacility $healthFacility, $version, $emergencyContent){
        $versionJson = new VersionJson();
        $versionJson->health_facility_id = $healthFacility->id;
        $versionJson->json = json_encode($version["medal_r_json"]);
        $versionJson->emergency_content = json_encode($emergencyContent);
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
