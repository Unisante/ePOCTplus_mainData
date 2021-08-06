<?php

namespace App;

use App\Device;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class HealthFacility extends Model
{
  protected $fillable = [
    "name",
    "user_id",
    "local_data_ip",
    "pin_code",
    "lat",
    "long",
    "country",
    "area",
    "group_id",
    "hf_mode",
  ];
  protected $guarded = [];

  public function medical_cases(){
    return $this->hasMany('App\MedicalCase','group_id','group_id');
  }
  public function log_cases(){
    return $this->hasMany('App\JsonLog','group_id','group_id');
  }
  public function patients(){
    return $this->hasMany('App\Patient','group_id','group_id');
  }
  public function versionJson(){
    return $this->hasOne('App\VersionJson');
  }

  public function devices(){
    return $this->hasMany(Device::class);
  }
}
