<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
  protected $guarded = [];

    public static function parse_data($medical_case,$node,$diagnoses){
      $proposed_diagnosis='proposed';
      $additional_diagnosis='';
        if(array_key_exists($proposed_diagnosis,$diagnoses) || array_key_exists($additional_diagnosis,$diagnoses)){

          foreach($diagnoses[$proposed_diagnosis] as $diagnosis){
            $agreed=$diagnosis['agreed'];
            $diagnosis_node=$node[$diagnosis['id']];
            $issued_diagnosis=Self::getOrCreate($medical_case,$diagnosis_node,$agreed);
            foreach($diagnosis_node['managements'] as $management_to_issue){
              $management_node=$node[$management_to_issue['id']];
              $management=Management::getOrCreateDiagnosis($issued_diagnosis,$management_node);
            }
            foreach($diagnosis_node['drugs'] as $drugs_to_issue){
              $drug_node=$node[$drugs_to_issue['id']];
              $drug=Drug::getOrCreateDiagnosis($issued_diagnosis,$drug_node);
            }
          }
        }
    }

    public static function getOrCreate($medical_case,$diagnosis_node,$agreed){
      //true stands for proposed  and false stands for aditional
      $diagnosis = Diagnosis::firstOrCreate(
        [
          'medal_c_id' => $diagnosis_node['id'], 'medical_case_id' => $medical_case->id
        ],
        [
          'type' => $diagnosis_node['type'],
          'reference' => $diagnosis_node['reference'],
          'label' => $diagnosis_node['label'],
          'diagnostic_id' => $diagnosis_node['diagnostic_id'],
          'agreed' => $agreed,'proposed/additional' => true,
        ]
      );
      return $diagnosis;
    }
}
