<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DateTime;
use App\Node;
use App\Answer;
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
    $caseAnswers=self::all();
    $cols = []; $pivot = [];
    foreach($caseAnswers as $record){
      if($record->answer_id != null){
        list($mdcaid, $node_id, $value) = array($record->id,$record->node->label,$record->answer->label) ;
      }else if($record->answer_id == null && $record->value == null){
        continue;
      }
      else{
        list($mdcaid, $node_id, $value) = array($record->id,$record->node->label,$record->value) ;
      }
      if (!array_key_exists($mdcaid,$pivot)) {
        $pivot[$mdcaid] = array();
      }
      array_push($cols, $node_id);
      array_push($pivot[$mdcaid], array('node_id' => $node_id, 'value' => $value));
    }
    $cols = array_unique($cols);
    array_unshift($cols , 'case_answer_id');
    array_unshift($cols , 'case_id');
    array_unshift($cols, 'patient');
    $file = fopen($filename,"w");
    fputcsv($file, $cols);
    foreach($caseAnswers as $record){
      $node_index=array_search($record['node_id'], $cols);
        $tempArr=[];
        $tempArr[0]=$record->medical_case->patient->local_patient_id;
        $tempArr[1]=$record->medical_case->local_medical_case_id;
        $tempArr[2]=$record->id;
        foreach($cols as $index=>$col){
          if($index == $node_index-1){
            array_push($tempArr,$record['value']);
          }else{
            array_push($tempArr,0);
          }
        }
        array_pop($tempArr);
        fputcsv($file, $tempArr);
    }
    fclose($file);
    return $filename;
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
