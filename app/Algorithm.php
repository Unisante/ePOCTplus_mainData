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

  // checks if it exists and if not,it creates the existance,if it does.It returns true
  public static function ifOrExists($data){
    $data_to_return=array();
    // check if the algorithm exist in the database
    $algorithm_doesnt_exist=Algorithm::where('medal_c_id',$data['algorithm_id'])->doesntExist();
    $version_doesnt_exist=Version::where('medal_c_id',$data['version_id'])->doesntExist();
    if($algorithm_doesnt_exist){
      $version_id=$data['version_id'];
      $medal_C_algorithm= self::fetchAlgorithm($version_id);
      // saving a new algorithm
      $algorithm = self::store($medal_C_algorithm['algorithm_name'],$medal_C_algorithm['algorithm_id']);
      // checking to see if there is a version of the algorithm
      $version = Version::store($medal_C_algorithm['version_name'],$medal_C_algorithm['version_id'],$algorithm->id);
      $config_questions = $medal_C_algorithm['config']['basic_questions'];
      PatientConfig::getOrCreate($config_questions,$version);
      // have to store the nodes for the algorithm
      $nodes = Node::getOrStore($medal_C_algorithm['nodes'],$algorithm);

      $diagnoses = Diagnosis::getOrStore($medal_C_algorithm['nodes'],$version->id);
      // saving the return array
      $data_to_return['inBeforeA']=False;
      $data_to_return['inBeforeV']=False;
      $data_to_return['algorithm_name']=$algorithm->name;
      $data_to_return['algorithm_id']=$algorithm->medal_c_id;
      $data_to_return['version_name']=$version->name;
      $data_to_return['version_id']=$version->medal_c_id;
    }
    else if ($version_doesnt_exist){
      $version_id=$data['version_id'];
      $medal_C_algorithm= self::fetchAlgorithm($version_id);
      // find the algorithm
      $algorithm=Algorithm::where('medal_c_id',$data['algorithm_id'])->first();
      // create a version
      $version = Version::store($medal_C_algorithm['version_name'],$medal_C_algorithm['version_id'],$algorithm->id);
      $config_questions = $medal_C_algorithm['config']['basic_questions'];
      PatientConfig::getOrCreate($config_questions,$version);
      $nodes = Node::getOrStore($medal_C_algorithm['nodes'],$algorithm);
      $diagnoses = Diagnosis::getOrStore($medal_C_algorithm['nodes'],$version->id);
      // remind the old man about diagnoses
      // saving the return array
      $data_to_return['inBeforeA']=True;
      $data_to_return['inBeforeV']=False;
      $data_to_return['algorithm_name']=$algorithm->name;
      $data_to_return['algorithm_id']=$algorithm->medal_c_id;
      $data_to_return['version_name']=$version->name;
      $data_to_return['version_id']=$version->medal_c_id;
    }else{
      $algorithm=Algorithm::where('medal_c_id',$data['algorithm_id'])->first();
      $version=Version::where('medal_c_id',$data['algorithm_id'])->first();

      // saving the return array
      $data_to_return['inBeforeA']=True;
      $data_to_return['inBeforeV']=True;
      $data_to_return['algorithm_name']=$algorithm->name;
      $data_to_return['algorithm_id']=$algorithm->medal_c_id;
      $data_to_return['version_name']=$version->name;
      $data_to_return['version_id']=$version->medal_c_id;
    }
    return $data_to_return;
  }
  public static function fetchAlgorithm($version_id){
    // setting headers for when we secure this part of quering from medal c
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //   'Header-Key: Header-Value',
    //   'Header-Key-2: Header-Value-2'
    // ));
    // $version_id=$data['version_id'];
    // dd($version_id);
    $url='https://liwi-test.wavelab.top/api/v1/versions/'.$version_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 400);
    $result=curl_exec($ch);
    curl_close($ch);
    $medal_C_algorithm = json_decode($result, true);
    return $medal_C_algorithm;
  }
  public static function store($name,$medal_c_id){
    $algorithm = new Algorithm;
    $algorithm->name = $name;
    $algorithm->medal_c_id = $medal_c_id;
    $algorithm->save();
    return $algorithm;
  }
}
