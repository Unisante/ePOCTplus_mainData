<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrugReference extends Model
{
  protected $guarded = [];

  /**
   * Make diagnoses relation
   * @return one to one drub Diagnosis
   */
  public function drugs() {
    return $this->hasOne('App\Drug','id','drug_id');
  }
  public function drug() {
    return $this->belongsTo('App\Drug','drug_id');
  }
  public function diagnosisReference(){
    return $this->belongsTo('App\DiagnosisReference','diagnosis_id');
  }
}
