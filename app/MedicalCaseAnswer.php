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

  /*
  * Checks if it key exist
  * @params $json
  * @params $medical_case
  * @return void
  */
  // public static function parse_answers($nodes, $medical_case,$algorithm){
  //   $group_one=["Boolean","Array","Present","Positive"];
  //   $group_two=["Integer","Float","Date","String"];
  //   foreach($nodes as $node){
  //     $check_value=isset($node['value'])?$node['value']:null;
  //     $check_answer=isset($node['answer'])?$node['answer']:null;
  //     if($check_value==null && $check_answer==null){
  //       continue;
  //     }
  //     if(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_one) && array_key_exists('answer',$node) && array_key_exists($node['answer'],$node['answers'])){
  //       $issued_node=Node::getOrCreate($node,$algorithm);
  //       foreach($node['answers'] as $answer){Answer::getOrCreate($issued_node,$answer);}
  //       $issued_value=isset($node['value'])?$node['value']:null;
  //       $medical_case_answer=Self::getOrCreate($medical_case,$node['answer'],$issued_node,$issued_value);
  //     }elseif(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_two)&& array_key_exists('value',$node)){
  //       $issued_node=Node::getOrCreate($node,$algorithm);
  //       $issued_value=$node['value'];
  //       $medical_case_answer=Self::getOrCreateValue($medical_case,$issued_node,$issued_value);
  //     }
  //   }
  // }
  // public static function getOrCreateValue($medical_case,$node,$issued_value){
  //   $main_d_answer_id=0;
  //   $medical_case_answer = MedicalCaseAnswer::firstOrCreate(
  //     [
  //       'medical_case_id'=>$medical_case->id,
  //       'answer_id'=>$main_d_answer_id,
  //       'node_id'=>$node->id
  //     ],
  //     [
  //       'value'=>$issued_value
  //     ]
  //   );
  // }
  public static function getOrCreate($nodes,$medical_case){
    // loop in the nodes
    foreach($nodes as $node){
      $node_issued= Node::where('medal_c_id',$node['id'])->first();
      $answer=isset($node['answer'])?$node['answer']:null;
      $value= isset($node['value'])?$node['value']:null;
      if($question_not_exist=Node::where('medal_c_id',$node['id'])->exists()){
        if($node['answer']){
          $get_answer= Answer::where('medal_c_id',$node['answer'])->first();
          $answer=$get_answer->id;
          self::store($medical_case->id,$answer,$node_issued->id,$value);
        }else{
          self::store($medical_case->id,$answer,$node_issued->id,$value);
        }
      }
    }
  }

  public static function store($medical_case_id,$answer_id,$node_id,$value){

    MedicalCaseAnswer::firstOrCreate(
      [
        'medical_case_id'=>$medical_case_id,
        'answer_id'=>$answer_id,
        'node_id'=>$node_id
      ],
      [
        'value'=>$value
      ]
    );
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
