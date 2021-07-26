<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Diagnosis;
use App\ManagementReference;
use App\DrugReference;
use App\Custom_diagnosis;
use App\Algorithm;
use App\Version;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Management;
class DiagnosisReference extends Model
{
  protected $guarded = [];

  /**
  * Checks the medical_case.json for diagnosis referencing
  * @params $medical_case_id
  * @params $diagnoses
  * @params $version_id
  * @return void
  */
  public static function parse_data($medical_case_id,$diagnoses,$version_id){
    $proposed_diagnoses=$diagnoses['proposed'];
    $additional_diagnoses=$diagnoses['additional'];
    $custom_diagnoses=$diagnoses['custom'];
    $additional_drugs=$diagnoses['additionalDrugs'];
    if($proposed_diagnoses){
      $is_proposed=True;
      self::store($medical_case_id,$proposed_diagnoses,$is_proposed,$version_id);
    }
    if($additional_diagnoses){
      $is_proposed=False;
      self::store($medical_case_id,$additional_diagnoses,$is_proposed,$version_id);
    }
    if($custom_diagnoses){
      Custom_diagnosis::store($custom_diagnoses,$medical_case_id);
    }
    if($additional_drugs){
      AdditionalDrug::store($additional_drugs,$medical_case_id,$version_id);
    }


  }

  /**
  * get or store diagnosis reference
  * @params $medical_case_id
  * @params $diagnoses
  * @params $is_proposed
  * @params $version_id
  * @return void
  */
  public static function store($medical_case_id,$diagnoses,$is_proposed,$version_id){
    foreach($diagnoses as $diagnosis){
      $managements=$diagnosis['managements'];
      $drugs = $diagnosis['drugs'];
      $agreed= isset($diagnosis['agreed'])?$diagnosis['agreed']:True;
      if(Diagnosis::where('medal_c_id',$diagnosis['id'])->doesntExist()){
        $medal_C_algorithm=Algorithm::fetchAlgorithm($version_id);
        $algorithm=Algorithm::where('medal_c_id',$medal_C_algorithm['algorithm_id'])->first();
        $version = Version::store($medal_C_algorithm['version_name'],$medal_C_algorithm['version_id'],$algorithm->id);
        Diagnosis::store(
          [
            "diagnostics"=>$medal_C_algorithm['diagnostics'],
            "is_arm_control"=>$medal_C_algorithm['is_arm_control'],
            "health_cares"=>$medal_C_algorithm['health_cares'],
            "version_id"=>$version->id
          ]
        );
      }
      if($local_diagnosis=Diagnosis::where('medal_c_id',$diagnosis['id'])->first()){
        $diagnosis=DiagnosisReference::firstOrCreate(
          [
            'medical_case_id'=>$medical_case_id,
            'diagnosis_id'=>$local_diagnosis->id,
          ],
          [
            'agreed'=>$agreed,
            'proposed_additional'=>$is_proposed
          ]
        );
        if($managements){
          foreach($managements as $management){
            $management_id=$management['id'];
            $issued_management=Management::where('medal_c_id',$management_id)->doesntExist();
            if($issued_management){
              $medal_C_algorithm=Algorithm::fetchAlgorithm($version_id);
              if(! isset($medal_C_algorithm['health_cares'][$management_id])){
                Log::info('IdInHealhCAre',  ['IdInHealhCAre' =>  'the id'.$management_id.' does not exist in healthcare in algo version'.$version_id]);
                continue;
              }
              if(array_key_exists($management_id,$medal_C_algorithm['health_cares'])){
                if($medal_C_algorithm['health_cares'][$management_id]['category'] == 'management'){
                  $saved_management=Management::firstOrCreate(
                    [
                      'medal_c_id'=>$medal_C_algorithm['health_cares'][$management_id]['id']
                    ],
                    [
                      'type'=>$medal_C_algorithm['health_cares'][$management_id]['type'],
                      'label'=>$medal_C_algorithm['health_cares'][$management_id]['label'][Config::get('medal.creator.language')],
                      'description'=>isset($medal_C_algorithm['health_cares'][$management_id]['description'][Config::get('medal.creator.language')])?$medal_C_algorithm['health_cares'][$management_id]['description'][Config::get('medal.creator.language')]:''
                    ]
                  );
                  ManagementReference::firstOrCreate(
                    [
                      'diagnosis_id'=>$diagnosis->id,
                      'management_id'=>$saved_management->id
                    ],
                    [
                      'agreed'=>$management['agreed']
                    ]
                  );
                }{
                  Log::info('DiagnosisReference',  ['management' => 'the id'.$management_id.' is not management']);
                }
              }else{
                Log::info('DiagnosisReference',  ['management' =>  'the id'.$management_id.' does not exist in healthcare in algo version'.$version_id]);
              }
            }else{
              $issued_management=Management::where('medal_c_id',$management_id)->first();
                ManagementReference::firstOrCreate(
                    [
                      'diagnosis_id'=>$diagnosis->id,
                      'management_id'=>$issued_management->id
                    ],
                    [
                      'agreed'=>$agreed
                    ]

                );
              }
          }
        }
        if($drugs){
          foreach($drugs as $drug){
            $drug_id=$drug['id'];
            $issued_drug=Drug::where('medal_c_id',$drug_id)->doesntExist();
            if($issued_drug){
              $medal_C_algorithm=Algorithm::fetchAlgorithm($version_id);
              if(! isset($medal_C_algorithm['health_cares'][$drug_id])){
                Log::info('IdInHealhCAre',  ['IdInHealhCAre' =>  'the id'.$drug_id.' does not exist in healthcare in algo version'.$version_id]);
                continue;
              }
              if(array_key_exists($drug_id,$medal_C_algorithm['health_cares'])){
                if($medal_C_algorithm['health_cares'][$drug_id]['category'] == 'drug'){
                  $is_anti_malarial= isset($medal_C_algorithm['health_cares'][$drug_id]['is_anti_malarial'])?$medal_C_algorithm['health_cares'][$drug_id]['is_anti_malarial']:null;
                  $is_antibiotic= isset($medal_C_algorithm['health_cares'][$drug_id]['is_antibiotic'])?$medal_C_algorithm['health_cares'][$drug_id]['is_antibiotic']:null;
                  $saved_drug=Drug::firstOrCreate(
                      [
                        'medal_c_id'=>$medal_C_algorithm['health_cares'][$drug_id]['id']
                      ],
                      [
                        'type'=>$medal_C_algorithm['health_cares'][$drug_id]['type'],
                        'label'=>$medal_C_algorithm['health_cares'][$drug_id]['label'][Config::get('medal.creator.language')],
                        'description'=>isset($h_care['description'][Config::get('medal.creator.language')])?$medal_C_algorithm['health_cares'][$drug_id]['description'][Config::get('medal.creator.language')]:'',
                        'is_antibiotic' => $is_antibiotic,
                        'is_anti_malarial' => $is_anti_malarial
                      ]
                    );
                    $agreed= isset($drug['agreed'])?$drug['agreed']:false;
                    $formulationSelected= isset($drug['formulationSelected'])?$drug['formulationSelected']:null;
                    $formulationSelected=$formulationSelected+1;
                    if($formulationSelected != null){
                      $issued_drug->formulations->each(function($formulation,$index)use (&$formulationSelected, &$designatedFormula){
                        if($formulationSelected == $index){
                          $designatedFormula=$formulation->id;
                        }
                      });
                    }
                    $designatedFormula=0;
                    DrugReference::firstOrCreate(
                      [
                        'diagnosis_id'=>$diagnosis->id,
                        'drug_id'=>$saved_drug->id
                      ],
                      [
                        'agreed'=>$agreed,
                        'formulationSelected'=>$designatedFormula
                      ]

                  );
                }else{
                  Log::info('DiagnosisReference',  ['drug' => 'the id'.$drug_id.' is not drug']);
                }
              }else{
                Log::info('DiagnosisReference',  ['drug' =>  'the id'.$drug_id.' does not exist in healthcare in algo version'.$version_id]);
              }
            }else{
              $issued_drug=Drug::where('medal_c_id',$drug['id'])->first();
              $agreed= isset($drug['agreed'])?$drug['agreed']:false;
              $formulationSelected= isset($drug['formulationSelected'])?$drug['formulationSelected']:null;
              $formulationSelected=$formulationSelected+1;
              if($formulationSelected != null){
                $issued_drug->formulations->each(function($formulation,$index)use (&$formulationSelected, &$designatedFormula){
                  if($formulationSelected == $index){
                    $designatedFormula=$formulation->id;
                  }
                });
              }
              $designatedFormula=0;
              DrugReference::firstOrCreate(
                [
                  'diagnosis_id'=>$diagnosis->id,
                  'drug_id'=>$issued_drug->id
                ],
                [
                  'agreed'=>$agreed,
                  'formulationSelected'=>$designatedFormula
                ]

            );
            }
          }
          // DrugReference::store($diagnosis->id,$drugs);
        }
      }

    }
  }

  /**
  * get or store diagnosis
  * @params $id
  * @return $diagnoses
  */
  public static function getDiagnoses($id){
    $references=DiagnosisReference::where('medical_case_id',$id)->get();
    $diagnoses=array();
    foreach($references as $reference){
      $diagnosis=Diagnosis::find($reference->diagnosis_id);
      $med_diag=(object)array(
        "agreed"=>$reference->agreed,
        "proposed"=>$reference->proposed_additional,
        "diagnosis_medal_c_id"=>$diagnosis->medal_c_id,
        "label"=>$diagnosis->label,
        "version"=>Version::find($diagnosis->versiod_id)
      );
      array_push($diagnoses,$med_diag);
    }
    return $diagnoses;
  }

  /**
  * Make diagnosis relation
  * @return one to many drub references retionship
  */
  public function drugReferences(){
    return $this->hasMany('App\DrugReference','id','diagnosis_id');
  }

  /**
  * Make diagnosis relation
  * @return one to many management references retionship
  */
  public function managementReferences(){
    return $this->hasMany('App\ManagementReference','id','diagnosis_id');
  }
}
