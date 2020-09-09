<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

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
        dd($node['answers'][$node['answer']]);
        //check if the node exists in the database and if it doesnt,create it
        
      }elseif(array_key_exists('value_format',$node) && in_array($node['value_format'], $group_two)){
        dd($node['value']);
      }
    }
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
