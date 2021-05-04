<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Drug;
use App\Formulation;

class Diagnosis extends Model
{
  protected $guarded = [];

  /**
  * get or store diagnosis
  * @params $nodes
  * @params $version_id
  * @return void
  */
  public static function getOrStore($nodes,$final_diagnoses,$is_arm_control,$version_id){
    foreach($nodes as $node){
      if(array_key_exists('diagnostic_id',$node) && $node['type']=='FinalDiagnostic'){
        $diagnosis=Diagnosis::firstOrCreate(
          [
            'medal_c_id'=>$node['id'],
            'diagnostic_id'=>$node['diagnostic_id']
          ],
          [
            'label'=>$node['label'][env('LANGUAGE')],
            'type'=>$node['type'],
            'version_id'=>$version_id
          ]
        );
        Drug::store($node['drugs'],$nodes,$diagnosis->id);
        Management::store($node['managements'],$nodes,$diagnosis->id);
      }
    }
    foreach($final_diagnoses as $diagnosis){
      if(array_key_exists('diagnostic_id',$diagnosis) && $diagnosis['type']=='FinalDiagnostic'){
        $diagnosis_final=Diagnosis::firstOrCreate(
          [
            'medal_c_id'=>$diagnosis['id'],
            'diagnostic_id'=>$diagnosis['diagnostic_id']
          ],
          [
            'label'=>$diagnosis['label'][env('LANGUAGE')],
            'type'=>$diagnosis['type'],
            'version_id'=>$version_id
          ]
        );
        Drug::store($diagnosis['drugs'],$final_diagnoses,$diagnosis_final->id);
        Management::store($diagnosis['managements'],$final_diagnoses,$diagnosis_final->id);
      }
    }
  }
  public static function store($d_data){
    foreach($d_data['diagnostics'] as $diagnostic){
      foreach($diagnostic['final_diagnostics'] as $diagnosis){
        if(array_key_exists('diagnostic_id',$diagnosis) && $diagnosis['type']=='FinalDiagnostic'){
          $diagnosis_final=Diagnosis::firstOrCreate(
            [
              'medal_c_id'=>$diagnosis['id'],
              'diagnostic_id'=>$diagnosis['diagnostic_id']
            ],
            [
              'label'=>$diagnosis['label'][env('LANGUAGE')],
              'type'=>$diagnosis['type'],
              'version_id'=>$d_data['version_id']
            ]
          );
          Drug::store($diagnosis['drugs'],$d_data['health_cares'],$diagnosis_final->id);
          Management::store($diagnosis['managements'],$d_data['health_cares'],$diagnosis_final->id);
        }
      }
    }
    foreach($d_data['health_cares'] as $h_care){
      if($h_care['category'] == 'drug'){
        $is_anti_malarial= isset($h_care['is_anti_malarial'])?$h_care['is_anti_malarial']:null;
        $is_antibiotic= isset($h_care['is_antibiotic'])?$h_care['is_antibiotic']:null;
        $drug=Drug::firstOrCreate(
          [
            'medal_c_id'=>$h_care['id']
          ],
          [
            'type'=>$h_care['type'],
            'label'=>$h_care['label'][env('LANGUAGE')],
            'description'=>isset($h_care['description'][env('LANGUAGE')])?$h_care['description'][env('LANGUAGE')]:'',
            'is_antibiotic' => $is_antibiotic,
            'is_anti_malarial' => $is_anti_malarial
          ]
        );
        Formulation::store($drug->id,$h_care['formulations']);
      }
    }
    // dd($d_data)
    // dd("we're here");
  }
  /**
  * Make drugs relation
  * @return one to many medical cases retionship
  */
  public function drugs(){
    return $this->hasMany('App\Drug');
  }

  /**
  * Make managements relation
  * @return one to many medical cases retionship
  */
  public function managements(){
    return $this->hasMany('App\Management');
  }

  /**
  * Make medical case relation
  * @return one to many medical cases retionship
  */
  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
}
