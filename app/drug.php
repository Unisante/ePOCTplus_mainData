<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formulation;
class Drug extends Model
{
  protected $guarded = [];

  public static function getOrCreateDiagnosis($diagnosis,$node){
    $data_to_search=array(
      'diagnosis_id'=>$diagnosis->id,
      'node_id'=>$node['id']
    );
    $drug_exist=Drug::where(['diagnosis_id' => $diagnosis->id,'medal_c_id' => $node['id']])->exists();
    if($drug_exist){
      $drug=Drug::where(['diagnosis_id' => $diagnosis->id,'medal_c_id' => $node['id']])->first();
    }else{
      $drug=Drug::create([
        'diagnosis_id' => $diagnosis->id,
        'medal_c_id' => $node['id'],
        'type' => $node['type'],
        'reference' => $node['reference'],
        'label' => $node['label'],
        'description' => $node['description'],
        'is_anti_malarial' => $node['is_anti_malarial'],
        'is_antibiotic' => $node['is_antibiotic'],
        'formulationSelected' => $node['formulationSelected'],
        'diagnosis_id' => $diagnosis->id,
        'custom_diagnosis_id' => null,
      ]);
      foreach($node['formulations'] as $formulation){
        Formulation::store($drug,$formulation);
      }
    }
  return $drug;
  }
}
