<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Drug;

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

}
