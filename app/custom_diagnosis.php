<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom_diagnosis extends Model
{
  protected $guarded = [];
  /**
  * store Custom_diagnosis
  * @params $diagnoses
  * @params $medical_case_id
  * @return void
  */
  public static function store($diagnoses,$medical_case_id){
    foreach($diagnoses as $diagnosis){
      Custom_diagnosis::firstOrCreate(
        [
        'medical_case_id'=>$medical_case_id,
        'label'=>$diagnosis['label']
        ],
        [
          'drugs'=>implode(',', $diagnosis['drugs']),
        ]
      );
    }
  }
}
