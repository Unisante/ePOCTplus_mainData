<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
  protected $guarded = [];

  // public static function getOrCreateDiagnosis($diagnosis,$node){
  //   $management = Management::firstOrCreate(
  //     [
  //       'diagnosis_id' => $diagnosis->id,
  //       'medal_c_id' => $node['id']
  //     ],
  //     [
  //       'type' => $node['type'],
  //       'reference' => $node['reference'],
  //       'label' => $node['label'],
  //       'diagnosis_id' => $diagnosis->id,
  //       'custom_diagnosis_id' => null,
  //     ]
  //   );
  //   return $management;
  // }

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
                'label'=>$node['label'],
                'description'=>$node['description']
              ]
            );
          }
        }
      }
  }
}
