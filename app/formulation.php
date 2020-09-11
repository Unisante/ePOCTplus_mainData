<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formulation extends Model
{
  protected $guarded = [];
  public static function store($drug,$formulation){
    $formulation = Formulation::Create(
      [
        'drug_id' => $drug->id,
        'medication_form' => $formulation['medication_form'],
        'administration_route_name' => $formulation['administration_route_name'],
        'liquid_concentration' => $formulation['liquid_concentration'],
        'dose_form' => $formulation['dose_form'],
        'unique_dose' => $formulation['unique_dose'],
        'by_age' => $formulation['by_age'],
        'minimal_dose_per_kg' => $formulation['minimal_dose_per_kg'],
        'maximal_dose_per_kg' => $formulation['maximal_dose_per_kg'],
        'maximal_dose' => $formulation['maximal_dose'],
        'description' => $formulation['description'],
        'doses_per_day' => $formulation['doses_per_day'],
      ]
    );
  return $formulation;
  }
}
