<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Version;

class Config extends Model
{
  protected $guarded = [];
  public static function getOrCreate($config,$version){
    $configurations=json_encode($config);
    $config_doesnt_exist=Config::where('version_id',$version->id)->doesntExist();
    if($config_doesnt_exist){
      $config= new Config;
      $config->version_id = $version->id;
      $config->config = $configurations;
      $config->save();
    }
  }

  public static function getConfig($version_id){
    $version = Version::where('medal_c_id',$version_id)->first();
    $config = Config::where('version_id',$version->id)->first();
    $config=json_decode($config->config);
    return $config;
  }

}
