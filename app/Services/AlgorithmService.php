<?php


namespace App\Services;

use App\Device;
use App\Services\Http;
use App\HealthFacility;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DeviceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class AlgorithmService {

    public function getAndStoreCreatorAlgorithms(){
        $response = Http::get(Config::get('medal.creator.algorithms_url'),[]);
    }


    public function getAlgorithm($medal_c_id){
        $response = Http::get(Config::get('medal.creator.algorithms_url') . $medal_c_id,[]);

    }


    private function saveAlgorithmJSON($medal_c_id,$jsonData){
        Algorithm::where('medal_c_id',$medal_c_id)->update(array('json' => $jsonData));
    }
    
}