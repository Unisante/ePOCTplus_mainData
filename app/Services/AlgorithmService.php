<?php


namespace App\Services;

use App\Algorithm;
use App\Services\Http;
use App\HealthFacility;
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

    public function assignVersionToHealthFacility(HealthFacility $healthFacility,$versionID){
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.versions_endpoint') . "/" . $versionID;
        $response = json_decode(Http::get($url,[]));
        if ($response->medal_r_json == null){
            throw new Exception('Invalid Response from Remote Server');
        }
        return json_decode($response);
    }
    
}