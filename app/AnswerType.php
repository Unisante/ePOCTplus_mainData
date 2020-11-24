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
  * @return $answertype_value
  */
  public static function getAnswerType($anwserType_id){
    return AnswerType::find($anwserType_id)->value;
  }

  /**
  * Create or get answertype
  * @params $value
  * @return $answertype
  */
  public static function getOrCreate($value){
    $answertype = AnswerType::firstOrCreate(
      [
        'value' => $value
      ]
    );
    return $answertype;
  }
}
