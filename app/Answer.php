<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Answer extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Fetch a particular answer
  * @params $anserid
  * @return $answer
  */
  public static function getAnswer($answer_id){
    $answer=Answer::find($answer_id);
    return $answer;
  }
}
