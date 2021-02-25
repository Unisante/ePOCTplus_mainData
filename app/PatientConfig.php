<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientConfig extends Model
{
  protected $table = 'patient_configs';
  protected $guarded = [];

  /**
  * Create or get patient configuration
  * @params $config
  * @params $version_id
  * @return $config_data
  */
  public static function getOrCreate($config,$version_id){
    $configurations=json_encode($config);
    $config_fetch=PatientConfig::firstOrCreate(
      [
        "version_id"=>$version_id,
      ],
      [
        "config"=>$configurations
      ]
    );
    $config_data=json_decode($config_fetch->config);
    return $config_data;
  }

  /**
  * get patient configuration
  * @params $version_id
  * @return $config
  */
  public static function getConfig($version_id){
    $config = PatientConfig::where('version_id',$version_id)->first();
    $config=json_decode($config->config);
    return $config;
  }
}
