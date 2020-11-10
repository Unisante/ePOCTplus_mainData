<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formulation;
class Drug extends Model
{
  protected $guarded = [];

  // public static function getOrCreateDiagnosis($diagnosis,$node){
  //   $data_to_search=array(
  //     'diagnosis_id'=>$diagnosis->id,
  //     'node_id'=>$node['id']
  //   );
  //   $drug_exist=Drug::where(['diagnosis_id' => $diagnosis->id,'medal_c_id' => $node['id']])->exists();
  //   if($drug_exist){
  //     $drug=Drug::where(['diagnosis_id' => $diagnosis->id,'medal_c_id' => $node['id']])->first();
  //   }else{
  //     $drug=Drug::create([
  //       'diagnosis_id' => $diagnosis->id,
  //       'medal_c_id' => $node['id'],
  //       'type' => $node['type'],
  //       'reference' => $node['reference'],
  //       'label' => $node['label'],
  //       'description' => $node['description'],
  //       'is_anti_malarial' => $node['is_anti_malarial'],
  //       'is_antibiotic' => $node['is_antibiotic'],
  //       'formulationSelected' => $node['formulationSelected'],
  //       'diagnosis_id' => $diagnosis->id,
  //       'custom_diagnosis_id' => null,
  //     ]);
  //     foreach($node['formulations'] as $formulation){
  //       Formulation::store($drug,$formulation);
  //     }
  //   }
  // return $drug;
  // }

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
