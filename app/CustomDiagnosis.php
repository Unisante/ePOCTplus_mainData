<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomDiagnosis extends Model
{
  protected $guarded = [];

  public function custom_drugs(){
    return $this->hasMany('App\CustomDrug','custom_diagnosis_id','id');
  }
}
