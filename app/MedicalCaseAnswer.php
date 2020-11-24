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
  * get or create medical case answers
  * @params $nodes
  * @params $medical_case
  * @return void
  */
  public static function getOrCreate($nodes,$medical_case){
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

  /*
  * store medical case answer
  * @params $medical_case_id
  * @params $answer_id
  * @params $node_id
  * @params $value
  * @return void
  */
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
