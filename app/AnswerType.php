<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AnswerType extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];
  /**
  * Get answer type
  * @params $answerType_id
  * @return $answertype
  */
  public static function getAnswerType($anwserType_id){
    $answertype=AnswerType::find($anwserType_id);
    return $answertype->value;
  }
}
