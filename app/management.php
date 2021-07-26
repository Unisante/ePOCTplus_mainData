<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Management extends Model
{
  protected $table='managements';
  protected $guarded = [];

  /**
  * store managements
  * @params $managements
  * @params $nodes
  * @params $diagnosis_id
  * @return void
  */
  public static function store($managements,$nodes,$diagnosis_id){
    foreach($nodes as $node){
      foreach($managements as $management){
        if($node['id']==$management['id']){
          Management::firstOrCreate(
            [
              'diagnosis_id'=>$diagnosis_id,
              'medal_c_id'=>$node['id']
            ],
            [
              'type'=>$node['type'],
              'label'=>$node['label'][Config::get('medal.creator.language')],
              'description'=>isset($node['description'][Config::get('medal.creator.language')])?$node['description'][Config::get('medal.creator.language')]:''
            ]
          );
        }
      }
    }
  }
}
