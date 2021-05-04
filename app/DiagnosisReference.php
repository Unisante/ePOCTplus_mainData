<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Diagnosis;
use App\ManagementReference;
use App\DrugReference;
use App\Custom_diagnosis;
use App\Algorithm;
use App\Version;
use App\Management;
class DiagnosisReference extends Model
{
  protected $guarded = [];

  /**
  * Checks the medical_case.json for diagnosis referencing
  * @params $medical_case_id
  * @params $diagnoses
  * @params $version_id
  * @return void
  */
  public static function parse_data($medical_case_id,$diagnoses,$version_id){
    $proposed_diagnoses=$diagnoses['proposed'];
    $additional_diagnoses=$diagnoses['additional'];
    $custom_diagnoses=$diagnoses['custom'];
    $additional_drugs=$diagnoses['additionalDrugs'];
    // dd($diagnoses);
    if($proposed_diagnoses){
      $is_proposed=True;
      self::store($medical_case_id,$proposed_diagnoses,$is_proposed,$version_id);
    }
    if($additional_diagnoses){
      // dd($additional_diagnoses);
      $is_proposed=False;
      self::store($medical_case_id,$additional_diagnoses,$is_proposed,$version_id);
    }
    if($custom_diagnoses){
      Custom_diagnosis::store($custom_diagnoses,$medical_case_id);
    }
    if($additional_drugs){
      AdditionalDrug::store($additional_drugs,$medical_case_id,$version_id);
    }


  }

  /**
  * get or store diagnosis reference
  * @params $medical_case_id
  * @params $diagnoses
  * @params $is_proposed
  * @params $version_id
  * @return void
  */
  public static function store($medical_case_id,$diagnoses,$is_proposed,$version_id){
    // dd($diagnoses);
    foreach($diagnoses as $diagnosis){
      // dd($diagnosis);
      $managements=$diagnosis['managements'];
      $drugs = $diagnosis['drugs'];
      $agreed= isset($diagnosis['agreed'])?$diagnosis['agreed']:True;
      // dd(Diagnosis::where('medal_c_id',$diagnosis['id'])->exists());
      if(Diagnosis::where('medal_c_id',$diagnosis['id'])->doesntExist()){
        $medal_C_algorithm=Algorithm::fetchAlgorithm($version_id);
        $algorithm=Algorithm::where('medal_c_id',$medal_C_algorithm['algorithm_id'])->first();
        $version = Version::store($medal_C_algorithm['version_name'],$medal_C_algorithm['version_id'],$algorithm->id);
        Diagnosis::store(
          [
            "diagnostics"=>$medal_C_algorithm['diagnostics'],
            "is_arm_control"=>$medal_C_algorithm['is_arm_control'],
            "health_cares"=>$medal_C_algorithm['health_cares'],
            "version_id"=>$version->id
          ]
        );
      }
      if($local_diagnosis=Diagnosis::where('medal_c_id',$diagnosis['id'])->first()){
        $diagnosis=DiagnosisReference::firstOrCreate(
          [
            'medical_case_id'=>$medical_case_id,
            'diagnosis_id'=>$local_diagnosis->id,
          ],
          [
            'agreed'=>$agreed,
            'proposed_additional'=>$is_proposed
          ]
        );
        if($managements){
          foreach($managements as $management){
            $issued_management=Management::where('medal_c_id',$management['id'])->doesntExist();
            if($issued_management){
              $medal_C_algorithm=Algorithm::fetchAlgorithm($version_id);
              foreach($medal_C_algorithm['health_cares'] as $h_care){
                if($h_care['category']=='management'){
                  Management::firstOrCreate(
                    [
                      'medal_c_id'=>$h_care['id']
                    ],
                    [
                      'type'=>$h_care['type'],
                      'label'=>$h_care['label'][env('LANGUAGE')],
                      'description'=>isset($h_care['description'][env('LANGUAGE')])?$h_care['description'][env('LANGUAGE')]:''
                    ]
                  );
                }
              }
            }
            $issued_management=Management::where('medal_c_id',$management['id'])->first();
            ManagementReference::firstOrCreate(
                [
                  'diagnosis_id'=>$diagnosis->id,
                  'management_id'=>$issued_management->id
                ],
                [
                  'agreed'=>$agreed
                ]

            );
          }
        }
        if($drugs){
          DrugReference::store($diagnosis->id,$drugs);
        }
      }

    }
  }

  /**
  * get or store diagnosis
  * @params $id
  * @return $diagnoses
  */
  public static function getDiagnoses($id){
    $references=DiagnosisReference::where('medical_case_id',$id)->get();
    $diagnoses=array();
    foreach($references as $reference){
      $diagnosis=Diagnosis::find($reference->diagnosis_id);
      $med_diag=(object)array(
        "agreed"=>$reference->agreed,
        "proposed"=>$reference->proposed_additional,
        "diagnosis_medal_c_id"=>$diagnosis->medal_c_id,
        "label"=>$diagnosis->label,
        "version"=>Version::find($diagnosis->versiod_id)
      );
      array_push($diagnoses,$med_diag);
    }
    return $diagnoses;
  }
}
