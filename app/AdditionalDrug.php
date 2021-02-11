<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Drug;

class AdditionalDrug extends Model
{
  protected $guarded = [];

  public static function store($drugs,$medical_case_id,$version_id){
    foreach($drugs as $drug){
      $agreed= isset($drug['agreed'])?$drug['agreed']:false;
      $formulationSelected= isset($drug['formulationSelected'])?$drug['formulationSelected']:false;
      $issued_drug=Drug::where('medal_c_id',$drug['id'])->first();
      if($issued_drug){
        AdditionalDrug::firstOrCreate(
          [
            'drug_id'=>$issued_drug->id,
            'medical_case_id'=>$medical_case_id,
            'version_id'=>$version_id,
          ],
          [
            'agreed'=>$agreed,
            'formulationSelected'=>$formulationSelected
          ]
        );
      }
    }
  }
}
