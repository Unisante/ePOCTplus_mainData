<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
  protected $guarded = [];
    public static function getOrCreate($config,$version){
      error_log('nimeingia');
      error_log($version->id);
      $configurations=json_encode($config);
      $config_doesnt_exist=Config::where('version_id',$version->id)->doesntExist();
      error_log($config_doesnt_exist);
      if($config_doesnt_exist){
        $config= new Config;
        $config->version_id = $version->id;
        $config->config = $configurations;
        $config->save();
      }
      error_log('natoka');
    }
}
