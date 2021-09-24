<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DateTime;
use App\Node;
use App\Answer;
use App\DiagnosisReference;
use App\DrugReference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
Use File;

class MedicalCaseAnswer extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
   * Get all audits of one medical case
   * @params $id
   * @return $all
   */
  public static function getAudit($id){
    $medicalCaseAnswer=MedicalCaseAnswer::find($id);
    return $medicalCaseAnswer->audits;
  }
  public function makeFlatCsv(){
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', '3600');
    $filename='caseAnswers.csv';
    $case_drug_id_list=[];$case_answers_array=[];
    $column_tofetch='consultation_date';
    // $cases = MedicalCase::whereDate($column_tofetch,'>=',$fromDate)->whereDate($column_tofetch,'<=',$toDate)->get();
    $cases=MedicalCase::all();
    foreach($cases as $case){
      foreach($case->medical_case_answers as $cas){
        array_push($case_answers_array,$cas);
      }
      foreach($case->diagnosesReferences as $df){
        if(count($df->drugReferences) > 0){
          array_push($case_drug_id_list,$case->id);
          break;
        }
      }
    }
    //for drugs
    $folder_name='flat_files';
    $drugFile_name='drugFlat.csv';
    $case_file_name='caseAnswersFlat.csv';
    if(! Storage::has($folder_name)){
      Storage::makeDirectory($folder_name);
    }
    Storage::put("$folder_name/$drugFile_name","");
    Storage::put("$folder_name/$case_file_name","");
    $drug_csv=null;
    // if(count($case_drug_id_list) > 0){
    //   $new_drug_instance=new DrugReference();
    //   $drug_csv=$new_drug_instance->makeFlatCsv($case_drug_id_list,$folder_name);
    // }
    $caseAnswers=$case_answers_array;
    $cols = []; $pivot = []; $cols_to_rotate=[];
      foreach($caseAnswers as $record){
        if($record->answer_id != null){
          $mdcaid=$record->id;$node_id=$record->node->label;$value =$record->answer->label;$col_to_rotate=$record->node_id;
        }else if($record->answer_id == null && $record->value == null){
          continue;
        }
        else{
          $mdcaid=$record->id;$node_id=$record->node->label;$value =$record->value;$col_to_rotate=$record->node_id;
        }
        if (!array_key_exists($mdcaid,$pivot)) {
          $pivot[$mdcaid] = array();
        }
        array_push($cols, $node_id);
        array_push($cols_to_rotate,$col_to_rotate);
        array_push($pivot[$mdcaid], array('node_id' => $node_id, 'value' => $value));
      }
    $cols = array_unique($cols);
    $cols_to_rotate = array_unique($cols_to_rotate);
    $filenames=[];
    array_unshift($cols , 'case_id');
    array_unshift($cols, 'patient');
    array_unshift($cols_to_rotate , 'case_id');
    array_unshift($cols_to_rotate, 'patient');
    $path=Storage::path("$folder_name/$case_file_name");
    $file = fopen($path,"w");
    fputcsv($file, $cols);
    foreach(collect($caseAnswers)->chunk(5000) as $index=>$cs){
      $this->flatPieces($cols,$cs,$file);
    }
    fclose($file);
    array_push($filenames,$drug_csv);
    array_push($filenames,$case_file_name);
    return array_filter($filenames);
  }

  public function flatPieces($cols,$caseAnswers,$file){
    foreach($caseAnswers->groupBy('medical_case_id') as $record_group){
      $tempArr=[];
      $key_value_arr=[];
      foreach($record_group as $caseAnswer){
        $key_value_arr[$caseAnswer->node->label]=$caseAnswer;
      }
      foreach($cols as $index=>$col){
          if($index == 0){
            $tempArr[$index]=$record_group[0]->medical_case->patient->local_patient_id;
          }else if($index == 1){
            $tempArr[$index]=$record_group[0]->medical_case->local_medical_case_id;
          }else if (array_key_exists($col,$key_value_arr)){
            $value_needed=$key_value_arr[$col]->answer_id?$key_value_arr[$col]->answer->label:$key_value_arr[$col]->value;
            $tempArr[$index]=$value_needed;
          }else{
            $tempArr[$index]=0;
          }
        }

      fputcsv($file, $tempArr);
    }
  }
  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
  public function answer(){
    return $this->belongsTo('App\Answer');
  }
  public function node(){
    return $this->belongsTo('App\Node');
  }
}
