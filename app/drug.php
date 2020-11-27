<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formulation;
class Drug extends Model
{
  protected $guarded = [];

  /**
  * store drugs
  * @params $drugs
  * @params $nodes
  * @params $diagnosis_id
  * @return void
  */
  public static function store($drugs,$nodes,$diagnosis_id){
    foreach($nodes as $node){
      foreach($drugs as $drug){
        if($node['id']==$drug['id']){
          $is_anti_malarial= isset($node['is_anti_malarial'])?$node['is_anti_malarial']:null;
          $is_antibiotic= isset($node['is_antibiotic'])?$node['is_antibiotic']:null;
          $drug=Drug::firstOrCreate(
            [
              'diagnosis_id'=>$diagnosis_id,
              'medal_c_id'=>$node['id']
            ],
            [
              'type'=>$node['type'],
              'label'=>$node['label'],
              'description'=>$node['description'],
              'is_antibiotic' => $is_antibiotic,
              'is_anti_malarial' => $is_anti_malarial
            ]
          );
          Formulation::store($drug->id,$node['formulations']);
        }
      }
    }
  }
}
