<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Answer extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  public static function getOrCreate($nodeSaved,$node){
    if(array_key_exists('answers',$node)){
      $answers=isset($node['answers'])?$node['answers']:[];
      foreach($answers as $answer){
        $answer_issued = Answer::firstOrCreate(
          [
            'medal_c_id' => $answer['id'],
            'node_id'=>$nodeSaved->id
          ],
          [
            'label' => $answer['label']
          ]
        );
      }
    }
    return True;
  }
}
