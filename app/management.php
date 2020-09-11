<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
  protected $guarded = [];

  public static function getOrCreateDiagnosis($diagnosis,$node){
    $management = Management::firstOrCreate(
      [
        'diagnosis_id' => $diagnosis->id,
        'medal_c_id' => $node['id']
      ],
      [
        'type' => $node['type'],
        'reference' => $node['reference'],
        'label' => $node['label'],
        'diagnosis_id' => $diagnosis->id,
        'custom_diagnosis_id' => null,
      ]
    );
    return $management;
  }
}
