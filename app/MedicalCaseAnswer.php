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
      $check_value=isset($node['value'])?$node['value']:null;
      $check_answer=isset($node['answer'])?$node['answer']:null;
      if($check_value==null && $check_answer==null){
        continue;
      }
      if(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_one) && array_key_exists($node['answer'],$node['answers'])){
        $issued_node=self::generalGetOrCreate($node,$algorithm,$medical_case);
        foreach($node['answers'] as $answer){Answer::getOrCreate($issued_node,$answer);}
      }elseif(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_two)){
        self::generalGetOrCreate($node,$algorithm,$medical_case);
      }
    }
  }
  public static function generalGetOrCreate($node,$algorithm,$medical_case){
    //check if the node exists in the database and if it doesnt,create it
    $issued_node=Node::getOrCreate($node,$algorithm);
    //check for medical case answer or create
    $issued_value=isset($node['value'])?$node['value']:null;
    $medical_case_answer=Self::getOrCreate($medical_case,$node['answer'],$issued_node,$issued_value);
    return $issued_node;
  }
  public static function getOrCreate($medical_case,$answer,$node,$issued_value){
    $issued_answer=isset($answer)?$answer:0;
    $main_d_answer_id=0;
    if(Answer::where('medal_c_id',$issued_answer)->exists()){
      $main_d_answer_id=Answer::where('medal_c_id',$issued_answer)->first()->id;
    }
    $medical_case_answer = MedicalCaseAnswer::firstOrCreate(
      [
        'medical_case_id'=>$medical_case->id,
        'answer_id'=>$main_d_answer_id,
        'node_id'=>$node->id
      ],
      [
        'value'=>$issued_value
      ]
    );
    return $medical_case_answer;
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
