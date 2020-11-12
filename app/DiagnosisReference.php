<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Diagnosis;
use App\ManagementReference;
use App\DrugReference;
use App\Custom_diagnosis;
use App\Algorithm;
use App\Version;
class DiagnosisReference extends Model
{
  protected $guarded = [];
  public static function parse_data($medical_case_id,$diagnoses,$version_id){
    $proposed_diagnoses=$diagnoses['proposed'];
    $additional_diagnoses=$diagnoses['additional'];
    $custom_diagnoses=$diagnoses['custom'];
    $is_proposed=False;
    // check if the function is from what type of diagnosis
    if($proposed_diagnoses){
      $is_proposed=True;
      self::store($medical_case_id,$proposed_diagnoses,$is_proposed,$version_id);
    }else if($additional_diagnoses){
      self::store($medical_case_id,$additional_diagnoses,$is_proposed,$version_id);
    }else if($custom_diagnoses){
      // Custom_diagnosis::store($custom_diagnoses,$medical_case_id);
    }
    // what to do with custom diagnoses and drugs
    // else if($custom_diagnoses){

    // }
    // dd('done with proposed');
  }

  public static function store($medical_case_id,$diagnoses,$is_proposed,$version_id){
    foreach($diagnoses as $diagnosis){
      $managements=$diagnosis['managements'];
      $drugs = $diagnosis['drugs'];
      if(Diagnosis::where('medal_c_id',$diagnosis['id'])->doesntExist()){
        // make sure you are fetching all diagnoses
        $medal_C_algorithm=Algorithm::fetchAlgorithm($version_id);
        Diagnosis::getOrStore($medal_C_algorithm['nodes'],$version_id);
      }
      if($local_diagnosis=Diagnosis::where('medal_c_id',$diagnosis['id'])->first()){
        $diagnosis=DiagnosisReference::firstOrCreate(
          [
            'medical_case_id'=>$medical_case_id,
            'diagnosis_id'=>$local_diagnosis->id,
          ],
          [
            'agreed'=>$diagnosis['agreed'],
            'proposed_additional'=>$is_proposed
          ]
        );
        if($managements){
          ManagementReference::store($local_diagnosis->id,$managements);
        }
        if($drugs){
          DrugReference::store($local_diagnosis->id,$drugs);
        }
        // dd("here again");
        // if($drugs){

        // }
        // what to do with its drugs if its proposed

        // if its not proposed,how do you link it with its drugs
      }

    }
  }

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
