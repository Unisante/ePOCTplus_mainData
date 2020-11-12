<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientConfig extends Model
{
  protected $table = 'patient_configs';
  protected $guarded = [];
  public static function getOrCreate($config,$version){
    $configurations=json_encode($config);
    $config_doesnt_exist=PatientConfig::where('version_id',$version->id)->doesntExist();
    if($config_doesnt_exist){
      $config= new PatientConfig;
      $config->version_id = $version->id;
      $config->config = $configurations;
      $config->save();
    }
  }

  public static function getConfig($version_id){
    $version = Version::where('medal_c_id',$version_id)->first();
    $config = PatientConfig::where('version_id',$version->id)->first();
    $config=json_decode($config->config);
    return $config;
  }
}
