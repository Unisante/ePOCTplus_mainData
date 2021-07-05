<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Drug;
use App\Formulation;

class Diagnosis extends Model
{
  protected $guarded = [];

  /**
  * Make drugs relation
  * @return one to many medical cases retionship
  */
  public function drugs(){
    return $this->hasMany('App\Drug');
  }

  /**
  * Make managements relation
  * @return one to many medical cases retionship
  */
  public function managements(){
    return $this->hasMany('App\Management');
  }

  /**
  * Make medical case relation
  * @return one to many medical cases retionship
  */
  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
}
