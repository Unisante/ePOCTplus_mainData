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
    return $this->hasMany('App\Formulation','id','drug_id');
  }
}
