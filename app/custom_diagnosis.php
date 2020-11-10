<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom_diagnosis extends Model
{
    public static function store($diagnoses,$medical_case_id){
      foreach($diagnoses as $diagnosis){
        Custom_diagnosis::firstOrCreate([
         'medical_case_id'=>$medical_case_id,
         'diagnosis'=>$diagnoses
        ]);
      }
    }
}
