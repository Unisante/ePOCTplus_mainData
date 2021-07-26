<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Formulation extends Model
{
  protected $guarded = [];

  /**
  * store drugs formulations
  * @params $drug_id
  * @params $formulations
  * @return void
  */
  public static function store($drug_id,$formulations){
    foreach($formulations as $formulation){
      $formulationToSave= new Formulation;
      $formulationToSave->drug_id = $drug_id;
      $formulationToSave->medication_form = $formulation['medication_form'];
      $formulationToSave->administration_route_category = $formulation['administration_route_category'];
      $formulationToSave->administration_route_name = $formulation['administration_route_name'];
      $formulationToSave->liquid_concentration = $formulation['liquid_concentration'];
      $formulationToSave->dose_form = $formulation['dose_form'];
      $formulationToSave->unique_dose = $formulation['unique_dose'];
      $formulationToSave->by_age = $formulation['by_age'];
      $formulationToSave->minimal_dose_per_kg = $formulation['minimal_dose_per_kg'];
      $formulationToSave->maximal_dose_per_kg = $formulation['maximal_dose_per_kg'];
      $formulationToSave->maximal_dose = $formulation['maximal_dose'];
      $formulationToSave->doses_per_day = $formulation['doses_per_day'];
      $formulationToSave->description = isset($formulation['description'][Config::get('medal.creator.language')])?$formulation['description'][Config::get('medal.creator.language')]:'';
      $formulationToSave->save();
    }
  }
}
