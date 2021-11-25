<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formulation;
use Illuminate\Support\Facades\Config;

class Drug extends Model
{
  protected $guarded = [];

  /**
  * Create a relation with answers
  * @return relation
  */
  public function formulations(){
    return $this->hasMany('App\Formulation','drug_id', 'id');
  }

  /**
  * Create a relation with additional drugs
  * @return relation
  */
  public function additional_drugs(){
    return $this->hasMany('App\AdditionalDrug','drug_id', 'id');
  }
}
