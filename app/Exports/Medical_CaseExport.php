<?php

namespace App\Exports;

use DB;
use App\MedicalCase;
use App\Answer;
use App\Node;
use App\Diagnosis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Medical_CaseExport implements FromCollection,
WithHeadings,
// ShouldAutoSize,
WithTitle,
WithEvents
// WithEvents,
// WithMapping
{

  public function headings():array
  {
    return [
      'medical_case_id',
      'medical_case_version_id',
      'medical_case_patient_id',
      'medical_case_created_at',
      'medical_case_updated_at',
      'medical_case_local_medical_case_id',
      'medical_case_consent',
      'medical_case_is_eligible',
      'medical_case_group_id',
      'medical_case_redcap'
    ];
  }
  private $heads;

  // public function headings():array
  // {
  //   $header=array();
  //   $headerToFind=array();
  //   // get the columns from medical case
  //   foreach(DB::getSchemaBuilder()->getColumnListing("medical_cases") as $md){
  //     if($md == 'consent' || $md == 'isEligible'){
  //       continue;
  //     }
  //     array_push($header,$md);
  //   }
  //   // get labels from node
  //   foreach(Node::all() as $node){
  //     $nod='Node_';
  //     $label = str_replace(' ', '_', $node->label);
  //     array_push($headerToFind,$nod .$label);
  //     $label = $nod .$node->id .'_' .$label;

  //     array_push($header,$label);
  //   }
  //   // get from diagnosis
  //   foreach(Diagnosis::all() as $diagnosis){
  //     $diag='Diag_';
  //     $label = str_replace(' ', '_', $diagnosis->label);
  //     // dd($label);
  //     array_push($header,$label);
  //   }
  //   self::setHeadings($headerToFind);
  //   return $header;
  // }
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
        $event->sheet->getStyle('A1:J1')->applyFromArray([
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
      return MedicalCase::all()->chunk(100);
    }
    // public function map($medicalCase):array{
    //   // dd(self::headings());
    //   $data_array=array(
    //     $medicalCase->id,
    //     $medicalCase->version_id,
    //     $medicalCase->patient_id,
    //     $medicalCase->created_at,
    //     $medicalCase->updated_at,
    //     $medicalCase->local_medical_case_id
    //     // time for nodes

    //   );
    //   $current_array_size=sizeof($data_array);
    //   $count=1;
    //   dd(self::getHeadings());
    //   foreach(self::getHeadings() as $column){
    //     if($count>$current_array_size){
    //       if(strstr($column, 'Node_') != false){
    //         $column = str_replace('Node_', '', $column);
    //         $column = str_replace('_', ' ', $column);
    //         $question=Node::where('label',$column)->first();
    //         dd($question->label);
    //         foreach($medicalCase->medical_case_answers as $mdanswer){
    //           if($mdanswer->node_id == $question->id){
    //             // dd($question->label);
    //             $answer=Answer::find($mdanswer->answer_id);
    //             if($mdanswer->answer_id){
    //               array_push($data_array,$answer->label);
    //             }else{
    //               array_push($data_array,$mdanswer->value);
    //             }
    //           break;
    //           }
    //           if($mdanswer->node_id== 1038){
    //             // dd($question->label);
    //             // dd("here");
    //           }
    //         }
    //       }else{
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
          return 'medical_cases';
        }
      }
