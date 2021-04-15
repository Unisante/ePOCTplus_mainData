<?php

namespace App\Exports;

use DB;
use App\MedicalCase;
use App\Answer;
use App\Node;
use App\Diagnosis;
use Formulation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MedicalCaseExport implements FromCollection,
WithHeadings,
// ShouldAutoSize,
WithTitle
// WithEvents,
// WithMapping
{

  // public function headings():array
  // {
  //   return [
  //     'Id',
  //     'version_id',
  //     'patient_id',
  //     'created_at',
  //     'updated_at',
  //     'local_medical_case_id'
  //   ];
  // }
  private $heads;

  public function headings():array
  {
    $header=array();
    $headerToFind=array();
    $medical_cases_column=DB::getSchemaBuilder()->getColumnListing("medical_cases");
    // get the columns from medical case
    foreach($medical_cases_column as $md){
      if($md == 'consent' || $md == 'isEligible'){
        continue;
      }
      array_push($header,$md);
    }
    // get labels from node
    foreach(Node::all() as $node){
      $nod='Node_';
      $label = str_replace(' ', '_', $node->label);
      array_push($headerToFind,$nod .$label);
      $label = $nod .$node->medal_c_id .'_' .$label;

      array_push($header,$label);
    }
    // get from diagnosis
    foreach(Diagnosis::distinct(['medal_c_id','proposed_additional'])->get() as $diagnosis){
      $diag='Diag_';
      $label = str_replace(' ', '_', $diagnosis->label);
      array_push($headerToFind,$diag .$label);
      if($diagnosis->proposed_additional){
        $label = 'Pro_' .$diag .$diagnosis->medal_c_id .'_' .$label;
      }else{
        $label = 'Add_' .$diag .$diagnosis->medal_c_id .'_' .$label;
      }

      array_push($header,$label);
    }
    self::setHeadings($headerToFind);
    return $header;
  }
  public function setHeadings($headings){
    $this->heads = $headings;
  }
  public function getHeadings(){
    return $this->heads;
  }
  public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:F1')->applyFromArray([
          'font'=>[
            'bold'=>true,
          ],

          ]);

        }
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      $medical_cases= array();
      $medical_cases_column=DB::getSchemaBuilder()->getColumnListing("medical_cases");
      foreach(MedicalCase::all() as $mdcase){
        $md=array(
          $mdcase->id,
          $mdcase->version_id,
          $mdcase->patient_id,
          $mdcase->created_at,
          $mdcase->updated_at,
          $mdcase->local_medical_case_id,
        );
        $current_array_size=sizeof($md);
        $count=1;
        $columns=self::getHeadings();
        foreach($medical_cases_column as $mdcolumn){
          if($mdcolumn == 'consent' || $mdcolumn == 'isEligible'){
            continue;
          }
          array_unshift($columns,$mdcolumn);
        }
        foreach($columns as $column){
          if($count>$current_array_size){
              // for nodes
              if(strstr($column, 'Node_') != false){
                $column = str_replace('Node_', '', $column);
                $column = str_replace('_', ' ', $column);
                $question=Node::where('label',$column)->first();
                $medical_case_nodes_array=[];
                foreach($mdcase->medical_case_answers as $mdanswer){
                  array_push($medical_case_nodes_array,$mdanswer->node_id);
                }
                if(in_array($question->id,$medical_case_nodes_array)){
                  foreach($mdcase->medical_case_answers as $mdanswer){
                    if($mdanswer->node_id == $question->id){
                      $answer=Answer::find($mdanswer->answer_id);
                      if($mdanswer->answer_id){
                        array_push($md,$answer->label);
                      }else{
                        array_push($md,$mdanswer->value);
                      }
                    break;
                    }
                  }
                }else{
                  $null_value='';
                  array_push($md,$null_value);
                }
              }elseif(strstr($column, 'Diag_') != false){
                $column = str_replace('Diag_', '', $column);
                $column = str_replace('_', ' ', $column);
                $diagnosis=Diagnosis::where('label',$column)->where('medical_case_id',$mdcase->id)->first();
                if($diagnosis){
                  if($diagnosis->agreed){
                    $mngmnt_array=array();
                    $drugs_array=array();
                    foreach($diagnosis->managements as $management){
                      $mgnmt_to_send=array(
                        "medal_c_id"=>$management->medal_c_id,
                        "label"=>$management->label,
                        "diagnosis_id"=>$management->diagnosis_id
                      );
                      array_push($mngmnt_array,$mgnmt_to_send);
                    }
                    foreach($diagnosis->drugs as $drug){
                      $drug_data=array(
                        "medal_c_id"=>$drug->medal_c_id,
                        "label"=>$drug->label,
                        "description"=>$drug->description,
                        "is_anti_malarial"=>$drug->is_anti_malarial,
                        "is_antibiotic"=>$drug->is_antibiotic
                      );
                      array_push($drugs_array,$drug_data);
                    }
                    array_push($md,array("agreed",$mngmnt_array,$drugs_array));
                  }else{
                    $mngmnt_array=array();
                    $drugs_array=array();
                    foreach($diagnosis->managements as $management){
                      $mgnmt_to_send=array(
                        "medal_c_id"=>$management->medal_c_id,
                        "label"=>$management->label,
                        "diagnosis_id"=>$management->diagnosis_id
                      );
                      array_push($mngmnt_array,$mgnmt_to_send);
                    }
                    foreach($diagnosis->drugs as $drug){
                      if($drug->formulationSelected){
                        $formu=Formulation::find($drug->formulationSelected);
                      }else{
                        $formu='Not Selected';
                      }
                      $drug_data=array(
                        "medal_c_id"=>$drug->medal_c_id,
                        "label"=>$drug->label,
                        "description"=>$drug->description,
                        "is_anti_malarial"=>$drug->is_anti_malarial,
                        "is_antibiotic"=>$drug->is_antibiotic,
                        "formulationSelected"=>$formu
                      );
                      array_push($drugs_array,$drug_data);
                    }
                    array_push($md,array("disagreed",$mngmnt_array,$drugs_array));
                  }

                }else{
                  $null_value='';
                  array_push($md,$null_value);
                }
              }
            }
            $count++;
        }
        array_push($medical_cases,$md);
      }
      // dd($medical_cases);
      $medical_cases=collect((object)$medical_cases);
      // dd($medical_cases);
      // dd(gettype($medical_cases));
      return $medical_cases;
    }
    // public function map($medicalCase):array{
    //   $medical_cases_column=DB::getSchemaBuilder()->getColumnListing("medical_cases");
    //   $data_array=array(
    //     $medicalCase->id,
    //     $medicalCase->version_id,
    //     $medicalCase->patient_id,
    //     $medicalCase->created_at,
    //     $medicalCase->updated_at,
    //     $medicalCase->local_medical_case_id
    //   );
    //   $current_array_size=sizeof($data_array);
    //   $count=1;
    //   $columns=self::getHeadings();
    //   foreach($medical_cases_column as $mdcolumn){
    //     if($mdcolumn == 'consent' || $mdcolumn == 'isEligible'){
    //       continue;
    //     }
    //     array_unshift($columns,$mdcolumn);
    //   }
    //   foreach($columns as $column){
    //     if($count>$current_array_size){
    //       // for nodes
    //       if(strstr($column, 'Node_') != false){
    //         $column = str_replace('Node_', '', $column);
    //         $column = str_replace('_', ' ', $column);
    //         $question=Node::where('label',$column)->first();
    //         $medical_case_nodes_array=[];
    //         // dd($medicalCase->medical_case_answers);
    //         foreach($medicalCase->medical_case_answers as $mdanswer){
    //           if($mdanswer->node_id == 0 ){
    //             // dd("here");
    //           }
    //           array_push($medical_case_nodes_array,$mdanswer->node_id);
    //         }
    //         dd($medical_case_nodes_array);
    //         if(in_array($question->id,$medical_case_nodes_array)){
    //           foreach($medicalCase->medical_case_answers as $mdanswer){
    //             if($mdanswer->node_id == $question->id){
    //               $answer=Answer::find($mdanswer->answer_id);
    //               if($mdanswer->answer_id){
    //                 array_push($data_array,$answer->label);
    //               }else{
    //                 array_push($data_array,$mdanswer->value);
    //               }
    //             break;
    //             }
    //           }
    //         }else{
    //           $null_value='';
    //           array_push($data_array,$null_value);
    //         }

    //       }
    //       // for diagnosis
    //       // elseif(strstr($column, 'Diag_') != false){
    //       //   $column = str_replace('Diag_', '', $column);
    //       //   $column = str_replace('_', ' ', $column);
    //       //   // dd($medicalCase->id);
    //       //   $diagnosis=Diagnosis::where('label',$column)->where('medical_case_id',$medicalCase->id)->first();
    //       //   if($diagnosis){

    //       //   }
    //       //   dd('here');
    //       // }
    //       else{
    //         $null_value='';
    //         array_push($data_array,$null_value);
    //       }
    //     }
    //     $count++;
    //   }
    //   return $data_array;
    // }








    // public function map($medicalCase) : array {
    //   // find the questions and the answers in the answers table;
    //   $case_answers=array();
    //   foreach($medicalCase->medical_case_answers as $mdans){
    //     $question=Node::find($mdans->node_id);
    //     if($mdans->answer_id != 0){
    //       $caseAns=[
    //         "id"=>$mdans->id,
    //         "medical_case_id"=>$mdans->medical_case_id,
    //         "answer_id"=>Answer::find($mdans->answer_id)->toArray(),
    //         "question"=>$question,
    //         "value"=>$mdans->value,
    //         "created_at"=>Carbon::parse($mdans->created_at)->toFormattedDateString(),
    //         "updated_at"=>Carbon::parse($mdans->updated_at)->toFormattedDateString(),
    //       ];
    //       $obj_caseAns=(object)$caseAns;
    //       array_push($case_answers,$obj_caseAns);
    //     }else{
    //       $caseAns=[
    //         "id"=>$mdans->id,
    //         "medical_case_id"=>$mdans->medical_case_id,
    //         "answer_id"=>$mdans->answer_id,
    //         "question"=>$question,
    //         "value"=>$mdans->value,
    //         "created_at"=>Carbon::parse($mdans->created_at)->toFormattedDateString(),
    //         "updated_at"=>Carbon::parse($mdans->updated_at)->toFormattedDateString(),
    //       ];
    //       $obj_caseAns=(object)$caseAns;
    //       array_push($case_answers,$obj_caseAns);
    //     }
    //   }
    //   // for the diagnosis;
    //   $diagnoses=array();
    //   foreach($medicalCase->diagnoses as $diagnosis){
    //     $d_array=array(
    //       "diagnoses"=>$diagnosis,
    //       "drugs"=>$diagnosis->drugs->toArray(),
    //       "managements"=>$diagnosis->managements->toArray(),
    //     );
    //     // dd($d_array);

    //     // dd($diagnosis->drugs);
    //   }

    //     $data_array=array(
    //       $medicalCase->local_medical_case_id,
    //       $medicalCase->version_id,
    //       $medicalCase->patient_id,
    //       $medicalCase->isEligible,
    //       Carbon::parse($medicalCase->created_at)->toFormattedDateString(),
    //       Carbon::parse($medicalCase->updated_at)->toFormattedDateString(),
    //       $case_answers,
    //       $medicalCase->diagnoses,
    //     );
    //     // foreach($medicalCase as $md){
    //     //   dd($md);
    //     //   $mdcase=[
    //     //     $md->id,
    //     //     $md->version_id,
    //     //     $md->created_at,
    //     //     $md->updated_at
    //     //   ];
    //     // }
    //     // foreach($medicalCase->medical_case_answers as $mdans){
    //     //   $question=Node::find($mdans->node_id);
    //     //   if($mdans->answer_id == 0){
    //     //     $mdcase=[
    //     //       $medicalCase->id,
    //     //       $medicalCase->version_id,
    //     //       $medicalCase->patient_id,
    //     //       $medicalCase->created_at,
    //     //       $medicalCase->updated_at,
    //     //       '',
    //     //       $mdans->value,
    //     //       $question->label,
    //     //     ];
    //     //   }else{

    //     //     $mdcase=[
    //     //       $medicalCase->id,
    //     //       $medicalCase->version_id,
    //     //       $medicalCase->patient_id,
    //     //       $medicalCase->created_at,
    //     //       $medicalCase->updated_at,
    //     //       '',
    //     //       Answer::find($mdans->answer_id)->label,
    //     //       $question->label,
    //     //     ];
    //     //   }

    //     //   array_push($data_array,$mdcase);
    //     // }

    //     // return [
    //     //       $medicalCase->medical_case_answers,
    //     //       Carbon::parse($medicalCase->patient->birthdate)->toFormattedDateString(),
    //     //       $medicalCase->patient->weight,
    //     //       $medicalCase->patient->gender,
    //     //       $medicalCase->local_medical_case_id,
    //     //       Carbon::parse($medicalCase->created_at)->toFormattedDateString(),
    //     //       Carbon::parse($medicalCase->updated_at)->toFormattedDateString(),
    //     //   ] ;
    //     // dd($data_array);
    //     return $data_array;
    // }
        public function title():string
        {
          return 'Medical Cases';
        }
      }
