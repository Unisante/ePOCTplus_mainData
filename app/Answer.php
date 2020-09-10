<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Answer extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  public static function getOrCreate($node,$answer){
    $answer_issued = Answer::firstOrCreate(
      [
        'medal_c_id' => $answer['id'],
        'node_id'=>$node->id
      ],
      [
        'label' => $answer['label']
      ]
    );
    return $answer_issued;
  }
}
