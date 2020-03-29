<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Node extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Get the Specific Question
  * @params $question_id
  * @return $question
  */
  public static function getQuestion($question_id){
    $question=Node::find($question_id);
    return $question;
  }
  
  /**
  * Create a relation with answers
  * @return relation
  */
  public function answers()
  {
    return $this->hasMany('App\Answer');
  }
}
