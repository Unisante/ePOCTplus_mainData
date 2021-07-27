<?php

namespace App;
use App\AnswerType;
use App\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Auditable;
class Node extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Create a relation with answers
  * @return relation
  */
  public function answers(){
    return $this->hasMany('App\Answer');
  }
}
