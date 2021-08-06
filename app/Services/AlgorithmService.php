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
     * Fetches the algorithms metadata from medal-creator 
     */
    public function getAlgorithmsMetadata(){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint');
        $response = Http::get($url,[]);
        return json_decode($response);
    }

    /**
     * Fetches the version metadata from medal-creator for a specific algorithm id
     */
    public function getVersionsMetadata($algorithmCreatorID){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') .  "/" .  $algorithmCreatorID . "/versions";
        $response = Http::get($url,[]);
        return json_decode($response);
    }

    /**
     * Fetches the version indexed by versionID from the creator, checks the validity of all fields,
     * assigns the version to the health facility and store the json in the appropriate table
     * The accesses table is also updated
     */
    public function assignVersionToHealthFacility(HealthFacility $healthFacility,$versionID){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.versions_endpoint') . "/" . $versionID;
        $version = json_decode(Http::get($url,[]),true);
        $requiredFields = ['medal_r_json','name','id','is_arm_control','medal_r_json_version'];
        foreach($requiredFields as $field){
            if ($version[$field] === null){
                throw new Exception("Response from creator does not contain required field: $field");
            }
        }
        $this->assignVersion($healthFacility,$version);
        $this->updateAccesses($healthFacility,$version);
    }

    /**
     * Checks if the health facility already has a version assigned to it and if so updates the json with the new one
     * present in the $version array, otherwise it creates a new row for it in the version_jsons table
     */
    public function assignVersion(HealthFacility $healthFacility,$version){
        $versionJson = VersionJson::where('health_facility_id',$healthFacility->id)->first();
        if ($versionJson == null){
            $this->addVersion($healthFacility,$version);
        }else{
            $this->updateVersion($versionJson,$version);
        }
    }

    /**
     * Updates the json of $versionJson with the one present in $version
     */
    public function updateVersion(VersionJson $versionJson,$version){
        $versionJson->json = json_encode($version);
        $versionJson->save();
    }

    /**
     * adds a new row in the version_jsons table with the json from $version assigned to the health facility $healthFacility
     */
    public function addVersion(HealthFacility $healthFacility,$version){
        $versionJson = new VersionJson();
        $versionJson->health_facility_id = $healthFacility->id;
        $versionJson->json = json_encode($version);
        $versionJson->save();
        $healthFacility->version_json_id = $versionJson->id;
        $healthFacility->save();
    }

    /**
     * Returns the list of previously used versions for the given $healthFacility
     */
    public function getArchivedAccesses(HealthFacility $healthFacility){
        return HealthFacilityAccess::where('health_facility_id',$healthFacility->id)->
                                     where('access',false)->get();
    }

    /**
     * Returns the version currently used by the health facility $healthFacility
     */
    public function getCurrentAccess(HealthFacility $healthFacility){
        return HealthFacilityAccess::where('health_facility_id',$healthFacility->id)->
                                     where('access',true)->first();
    }
    /**
     * Updates the list of versions used for $healthFacility with the new version stored in $version
     */
    private function updateAccesses(HealthFacility $healthFacility,$version){
        $access = HealthFacilityAccess::where('health_facility_id',$healthFacility->id)->
                                        where('access',true)->
                                        first();
        if ($access != null){
            $this->archiveAccess($access);
        }
        $this->newAccess($healthFacility,$version);
    }

    /**
     * Creates a new version entry in the accesses table for the $healthFacility
     */
    private function newAccess(HealthFacility $healthFacility,$version){
        $access = new HealthFacilityAccess();
        $access->access = true;
        $access->creator_version_id = $version['id'];
        $access->version_name = $version['name'];
        $access->medal_r_json_version = $version['medal_r_json_version'];
        $access->is_arm_control = $version['is_arm_control'];
        $access->health_facility_id = $healthFacility->id;
        $access->save();
    }

    /**
     * Sets the version $access to archived by updating the access flag and setting 
     * the timestamp for the end date to the current datetime
     */
    private function archiveAccess(HealthFacilityAccess $access){
        $access->access= false;
        $access->end_date = now();
        $access->save();
    }

    /**
     * Returns the JSON of the algorithm version assigned to the given device $device
     */
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
    
}