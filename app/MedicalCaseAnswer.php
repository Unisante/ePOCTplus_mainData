<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Node;
class MedicalCaseAnswer extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /*
  * Checks if it key exist
  * @params $json
  * @params $medical_case
  * @return void
  */
  public static function parse_answers($nodes, $medical_case,$algorithm)
  {
    $group_one=["Boolean","Array","Present","Positive"];
    $group_two=["Integer","Float","Date","String"];
    foreach($nodes as $node){
      if(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_one) && array_key_exists($node['answer'],$node['answers'])){
        // dd($node['answers'][$node['answer']]);
        //check if the node exists in the database and if it doesnt,create it
        $issue_node=Node::getOrCreate($node,$algorithm);
        //save the answers
        $answer = Answer::getOrCreate();
        //We parse answers
        // $medical_case_answer=Self::getOrCreate();
      }elseif(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_two)){
        $issue_node=Node::getOrCreate($node,$algorithm);
        // $medical_case_answer=Self::getOrCreate();
      }
    }
  }

  public static function getOrCreate($medical_case,$answer,$value,$node){
    if($value==null){
      $medica_case_answer = MedicalCaseAnswer::firstOrCreate(
        [
          'medical_case_id'=>$medical_case->id,
          'answer_id'=>$answer_id
        ],
        [
          'node_id'=>$node
        ]
      );
    }else{

    }
    return $medica_case_answer;
  }
  /**
   * Get all audits of one medical case
   * @params $id
   * @return $all
   */
  public static function getAudit($id){
    $medicalCaseAnswer=MedicalCaseAnswer::find($id);
    return $medicalCaseAnswer->audits;
  }
  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
}
