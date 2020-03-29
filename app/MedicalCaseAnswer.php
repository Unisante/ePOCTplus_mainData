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
  public static function parse_answers($json, $medical_case)
  {
    foreach ($json['nodes'] as &$node){
      if (array_key_exists('answer', $node) && array_key_exists('value', $node) && ($node['answer'] != null || $node['value'] != null)){
        $answer_id = $node['answer'] != null ? $node['answer'] : 0;
        $value = $node['value'] != null ? $node['value'] : '';
        MedicalCaseAnswer::create(['medical_case_id' => $medical_case->id, 'answer_id' => $answer_id, 'value' => $value, 'node_id' => $node['id']]);
      }
    }
  }
  public function medical_case()
  {
    return $this->belongsTo('App\MedicalCase');
  }
}
