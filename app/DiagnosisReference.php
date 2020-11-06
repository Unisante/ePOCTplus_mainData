<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Diagnosis;
use App\ManagementReference;
class DiagnosisReference extends Model
{
  protected $guarded = [];
  public static function parse_data($medical_case_id,$diagnoses){
    $proposed_diagnoses=$diagnoses['proposed'];
    $additional_diagnoses=$diagnoses['additional'];
    $custom_diagnoses=$diagnoses['additional'];
    $is_proposed=False;
    // check if the function is from what type of diagnosis
    if($proposed_diagnoses){
      $is_proposed=True;
      self::store($medical_case_id,$proposed_diagnoses,$is_proposed);
    }else if($additional_diagnoses){
      self::store($medical_case_id,$additional_diagnoses,$is_proposed);
    }
    // what to do with custom diagnoses and drugs
    // else if($custom_diagnoses){

    // }
    dd('done with proposed');
  }

  public static function store($medical_case_id,$diagnoses,$is_proposed){
    foreach($diagnoses as $diagnosis){
      $managements=$diagnosis['managements'];
      $drugs = $diagnosis['drugs'];
      if($local_diagnosis=DIagnosis::where('medal_c_id',$diagnosis['id'])->first()){
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
        dd("here again");
        // if($drugs){

        // }
        // what to do with its drugs if its proposed

        // if its not proposed,how do you link it with its drugs
      }
    }
  }
}
