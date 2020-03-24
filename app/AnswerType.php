<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerType extends Model
{
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
