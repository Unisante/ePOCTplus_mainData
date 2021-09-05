<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DateTime;
use App\Node;
use App\Answer;
use App\DiagnosisReference;
use App\DrugReference;
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
  public function makeFlatCsv($filename,$fromDate,$toDate){
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', '3600');
    $filename=$filename?$filename:'ibuFlat.csv';
    $case_drug_id_list=[];$case_answers_array=[];
    $column_tofetch='consultation_date';
    $cases = MedicalCase::whereDate($column_tofetch,'>=',$fromDate)->whereDate($column_tofetch,'<=',$toDate)->get();
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
    // dd($case_drug_id_list);
    $drug_csv=null;
    if(count($case_drug_id_list) > 0){
      $new_drug_instance=new DrugReference();
      $drug_csv=$new_drug_instance->makeFlatCsv($case_drug_id_list);
    }
    $caseAnswers=$case_answers_array;
    $cols = []; $pivot = [];
      foreach($caseAnswers as $record){
        if($record->answer_id != null){
          $mdcaid=$record->id;$node_id=$record->node->label;$value =$record->answer->label;
        }else if($record->answer_id == null && $record->value == null){
          continue;
        }
        else{
          $mdcaid=$record->id;$node_id=$record->node->label;$value =$record->value;
        }
        if (!array_key_exists($mdcaid,$pivot)) {
          $pivot[$mdcaid] = array();
        }
        array_push($cols, $node_id);
        array_push($pivot[$mdcaid], array('node_id' => $node_id, 'value' => $value));
      }
    $cols = array_unique($cols);
    $filenames=[];
    $filename='caseAnswersFlat.csv';
    array_unshift($cols , 'case_answer_id');
    array_unshift($cols , 'case_id');
    array_unshift($cols, 'patient');
    $file = fopen($filename,"w");
    fputcsv($file, $cols);
    foreach(collect($caseAnswers)->chunk(5000) as $index=>$cs){
      $this->flatPieces($cols,$cs,$file);
    }
    fclose($file);
    array_push($filenames,$drug_csv);
    array_push($filenames,$filename);
    return array_filter($filenames);
  }

  public function flatPieces($cols,$caseAnswers,$file){
    foreach($caseAnswers as $record){
      $tempArr=[];
      $node_index=array_search($record->node->label, $cols);
      foreach($cols as $index=>$col){
        if($index == 0){
          array_push($tempArr,$record->medical_case->patient->local_patient_id);
        }else if($index == 1){
          array_push($tempArr,$record->medical_case->local_medical_case_id);
        }else if($index == 2){
          array_push($tempArr,$record->id);
        }else if($index == $node_index){
          array_push($tempArr,$record->answer_id?$record->answer->label:$record->value);
        }else{
          array_push($tempArr,0);
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
