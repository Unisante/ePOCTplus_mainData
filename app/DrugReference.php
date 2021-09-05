<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Drug;
use App\DiagnosisReference;
use App\MedicalCase;

class DrugReference extends Model
{
  protected $guarded = [];

  function makeFlatCsv($case_drug_id_list){
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', '3600');
    $drugs_needed=[];
    $cases = MedicalCase::find($case_drug_id_list);
    foreach($cases as $indexcase=>$case){
      foreach($case->diagnosesReferences as $indexdf=>$df){
        foreach($df->drugReferences as $drf){
          $drugs_needed[$drf->id]=$drf;
        }
      }
    }
    // dd($drugs_needed);
    ksort($drugs_needed);
    $filename='drugFlat.csv';
    $cols = []; $pivot = [];
    foreach($drugs_needed as $record){
      $drug_row=$record->id;$drug_column=$record->drug->label;$drug_value=$record->agreed;
      if (!array_key_exists($drug_row,$pivot)) {
        $pivot[$drug_row] = array();
      }
      array_push($cols, $drug_column);
      array_push($pivot[$drug_row], array('drug_column' => $drug_column, 'value' => $drug_value));
    }
    $cols = array_unique($cols);
    // array_unshift($cols , 'diagnosis_agreed');
    array_unshift($cols , 'diagnosis_proposed');
    array_unshift($cols , 'diagnoses');
    array_unshift($cols , 'case_id');
    array_unshift($cols , 'drug_reference_id');
    $file = fopen($filename,"w");
    fputcsv($file, $cols);
    foreach($drugs_needed as $record){
      $column_index=array_search($record->drug->label, $cols);
        $tempArr=[];
        foreach($cols as $index=>$col){
          if($index == 0){
            array_push($tempArr,$record->id);
          }else if($index == 1){
            array_push($tempArr,$record->diagnosisReference->medicalCase->local_medical_case_id);
          }else if($index == 2){
            array_push($tempArr,$record->diagnosisReference->diagnosis->label);
          }
          else if($index == 3){
            $record->diagnosisReference->diagnosis->additional?array_push($tempArr,0): array_push($tempArr,1);
          }
          // else if($index == 4){
          //   $record->diagnosisReference->diagnosis->agreed?array_push($tempArr,1): array_push($tempArr,0);
          // }
          else if($index == $column_index){
            array_push($tempArr,$record->agreed);
          }else{
            array_push($tempArr,0);
          }
        }
        fputcsv($file, $tempArr);
    }
    fclose($file);
    return $filename;
  }
  public function diagnosisReference(){
    return $this->belongsTo('App\DiagnosisReference','diagnosis_id',);
  }
  public function drug(){
    return $this->belongsTo('App\Drug','drug_id',);
  }
}
