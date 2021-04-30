<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Version;
use App\Node;
use App\PatientConfig;
class Algorithm extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Checks the the algorithm version
  * @params $algorithm_id
  * @params $version_id
  * @return array
  */
  public static function ifOrExists($data){
    // check if the algorithm exist in the database
    $algorithm_doesnt_exist=Algorithm::where('medal_c_id',$data['algorithm_id'])->doesntExist();
    $version_doesnt_exist=Version::where('medal_c_id',$data['version_id'])->doesntExist();
    if($algorithm_doesnt_exist){
      $version_id=$data['version_id'];
      $medal_C_algorithm= self::fetchAlgorithm($version_id);
      // saving a new algorithm
      $algorithm= Algorithm::firstOrCreate([
        "name"=>$medal_C_algorithm['algorithm_name'],
        "medal_c_id"=>$medal_C_algorithm['algorithm_id']
      ]);
      // checking to see if there is a version of the algorithm
      $version = Version::store($medal_C_algorithm['version_name'],$medal_C_algorithm['version_id'],$algorithm->id);
      $config_questions = $medal_C_algorithm['config']['basic_questions'];
      $config_data=PatientConfig::getOrCreate($version);
      // have to store the nodes for the algorithm
      Node::getOrStore($medal_C_algorithm['nodes'],$algorithm);
      $diagnoses = Diagnosis::getOrStore($medal_C_algorithm['nodes'],$version->id);
      return [
        "version_id"=>$version->id,
        "config_data"=>$config_data,
      ];
    }
    else if ($version_doesnt_exist){
      $version_id=$data['version_id'];
      $medal_C_algorithm= self::fetchAlgorithm($version_id);
      // find the algorithm
      $algorithm=Algorithm::where('medal_c_id',$data['algorithm_id'])->first();
      // create a version
      $version = Version::store($medal_C_algorithm['version_name'],$medal_C_algorithm['version_id'],$algorithm->id);
      $config_questions = $medal_C_algorithm['config']['basic_questions'];
      $config_data=PatientConfig::getOrCreate($version);
      Node::getOrStore($medal_C_algorithm['nodes'],$algorithm);
      $diagnoses = Diagnosis::getOrStore($medal_C_algorithm['nodes'],$version->id);
      return [
        "version_id"=>$version->id,
        "config_data"=>$config_data,
      ];
    }else{
      $version =Version::where('medal_c_id',$data['version_id'])->first();
      $config_data=PatientConfig::getOrCreate($version);
      return [
        "version_id"=>$version->id,
        "config_data"=>$config_data
      ];
    }
  }

  /**
  * fetch algorithm version from medal c
  * @params $version_id
  * @return array
  */
  public static function fetchAlgorithm($version_id){
    // setting headers for when we secure this part of quering from medal c
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //   'Header-Key: Header-Value',
    //   'Header-Key-2: Header-Value-2'
    // ));
    $curl = curl_init();
    curl_setopt_array($curl, array(
      // CURLOPT_URL => 'https://liwi-test.wavelab.top/api/v1/versions/'.$version_id,
      CURLOPT_URL => 'https://medalc.unisante.ch/api/v1/versions/'.$version_id,
      // CURLOPT_URL => 'https://liwi.wavelab.top/api/v1/versions/'.$version_id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Cache-Control: no-cache",
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return response()->json([
        "error"=>json_decode($err, true)
      ]);
    } else {
      $medal_C_algorithm = json_decode($response, true);
      return $medal_C_algorithm;
    }
  }

  /**
  * store algorithm
  * @params $name
  * @params $medal_c_id
  * @return algorithm
  */
  public static function store($name,$medal_c_id){
    $algorithm = new Algorithm;
    $algorithm->name = $name;
    $algorithm->medal_c_id = $medal_c_id;
    $algorithm->save();
    return $algorithm;
  }
}
