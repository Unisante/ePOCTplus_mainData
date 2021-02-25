<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Drug;

class DrugReference extends Model
{
  protected $guarded = [];

  /**
  * store drugs references
  * @params $diagnosis_id
  * @params $drugs
  * @params $diagnosis_id
  * @return void
  */
  public static function store($diagnosis_id,$drugs){
    foreach($drugs as $drug){
      $agreed= isset($drug['agreed'])?$drug['agreed']:false;
      $formulationSelected= isset($drug['formulationSelected'])?$drug['formulationSelected']:false;
      $issued_drug=Drug::where('medal_c_id',$drug['id'])->first();
      if($issued_drug){
        DrugReference::firstOrCreate(
          [
            'diagnosis_id'=>$diagnosis_id,
            'drug_id'=>$issued_drug->id
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
