<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
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
