<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formulation extends Model
{
  protected $guarded = [];
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
      $formulationToSave->description = $formulation['description'];
      $formulationToSave->save();
    }
  }
}
