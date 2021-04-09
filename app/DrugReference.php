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
      $designatedFormula=0;
      $issued_drug->formulations->each(function($formulation,$index)use (&$formulationSelected, &$designatedFormula){
        if($formulationSelected == $index){
          // dd($formulation->id);
          $designatedFormula=$formulation->id;
        }
      });
      // if($formulationSelected && $formulationSelected != 0){
      //   $allDrugFormulations=$issued_drug->formulations->toArray();
      //   $designatedFormula=$allDrugFormulations[$formulationSelected]['id'];
      // }
      if($issued_drug && array_key_exists('agreed',$drug)){
        DrugReference::firstOrCreate(
          [
            'diagnosis_id'=>$diagnosis_id,
            'drug_id'=>$issued_drug->id
          ],
          [
            'agreed'=>$agreed,
            'formulationSelected'=>$designatedFormula
          ]
        );
      }
    }
  }
}
