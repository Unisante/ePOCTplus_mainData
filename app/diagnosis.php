<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Drug;

class Diagnosis extends Model
{
  protected $guarded = [];

    public static function parse_data($medical_case,$node,$diagnoses){
      $proposed_diagnosis='proposed';
      $additional_diagnosis='additional';

        if(array_key_exists($proposed_diagnosis,$diagnoses) || array_key_exists($additional_diagnosis,$diagnoses)){

          foreach($diagnoses[$proposed_diagnosis] as $diagnosis){
            $agreed=$diagnosis['agreed'];
            $diagnosis_node=$node[$diagnosis['id']];
            $issued_diagnosis=Self::getOrCreate($medical_case,$diagnosis_node,$agreed,$proposed_diagnosis);
            foreach($diagnosis_node['managements'] as $management_to_issue){
              $management_node=$node[$management_to_issue['id']];
              $management=Management::getOrCreateDiagnosis($issued_diagnosis,$management_node);
            }
            foreach($diagnosis_node['drugs'] as $drugs_to_issue){
              $drug_node=$node[$drugs_to_issue['id']];
              $drug=Drug::getOrCreateDiagnosis($issued_diagnosis,$drug_node);
            }
          }
          // for additional diagnosis
          foreach($diagnoses[$additional_diagnosis] as $diagnosis){
            $agreed=$diagnosis['agreed'];
            $diagnosis_node=$node[$diagnosis['id']];
            $issued_diagnosis=Self::getOrCreate($medical_case,$diagnosis_node,$agreed,$additional_diagnosis);
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

    public static function getOrCreate($medical_case,$diagnosis_node,$agreed,$diagnosis_type){
      //true stands for proposed  and false stands for aditional
      if($diagnosis_type=='proposed'){
        $diagnosis = Diagnosis::firstOrCreate(
          [
            'medal_c_id' => $diagnosis_node['id'], 'medical_case_id' => $medical_case->id
          ],
          [
            'type' => $diagnosis_node['type'],
            'reference' => $diagnosis_node['reference'],
            'label' => $diagnosis_node['label'],
            'diagnostic_id' => $diagnosis_node['diagnostic_id'],
            'agreed' => $agreed,'proposed_additional' => true,
          ]
        );
      }else{
        $diagnosis = Diagnosis::firstOrCreate(
          [
            'medal_c_id' => $diagnosis_node['id'], 'medical_case_id' => $medical_case->id
          ],
          [
            'type' => $diagnosis_node['type'],
            'reference' => $diagnosis_node['reference'],
            'label' => $diagnosis_node['label'],
            'diagnostic_id' => $diagnosis_node['diagnostic_id'],
            'agreed' => $agreed,'proposed_additional' => false,
          ]
        );
      }

      return $diagnosis;
    }
    public static function getOrStore($nodes,$version_id){
      foreach($nodes as $node){
        if(array_key_exists('diagnostic_id',$node) && $node['type']=='FinalDiagnostic'){
          $diagnosis=Diagnosis::firstOrCreate(
            [
              'medal_c_id'=>$node['id'],
              'diagnostic_id'=>$node['diagnostic_id']
            ],
            [
              'label'=>$node['label'],
              'type'=>$node['type'],
              'version_id'=>$version_id
            ]
          );
          Drug::store($node['drugs'],$nodes,$diagnosis->id);
          Management::store($node['managements'],$nodes,$diagnosis->id);
        }
      }
      return true;
    }

  public function drugs(){
    return $this->hasMany('App\Drug');
  }
  public function managements(){
    return $this->hasMany('App\Management');
  }
  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
}
