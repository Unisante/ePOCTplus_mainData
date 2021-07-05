<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthFacility extends Model
{
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
}
