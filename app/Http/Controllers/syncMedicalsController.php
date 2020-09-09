<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\MedicalCase;
class syncMedicalsController extends Controller
{
    public function syncMedicalCases(Request $request){
      $data1=$request->getContent();
      $data=$request->json()->all();
      $study_id='Test';
      foreach($data as $individualData){
        $patient=new Patient;

        $patient_key=$individualData['patient'];
        if($patient_key['study_id']== $study_id){
          $patient->local_patient_id=$patient_key['uid'];
          //gain the id to search in the nodes
          $config=$individualData['config'];
          $birth_date_question_id=$config['basic_questions']['birth_date_question_id'];
          $first_name_question_id=$config['basic_questions']['first_name_question_id'];
          $last_name_question_id=$config['basic_questions']['last_name_question_id'];
          $weight_question_id=$config['basic_questions']['weight_question_id'];
          $gender_question_id=$config['basic_questions']['gender_question_id'];

          //find the values in the node
          $nodes=$individualData['nodes'];
          foreach ($nodes as $node){
            if($node['id']==$birth_date_question_id){$patient->birthdate=$node['value'];}
            if($node['id']==$first_name_question_id){$patient->first_name=$node['value'];}
            if($node['id']==$last_name_question_id){$patient->last_name=$node['value'];}
            if($node['id']==$weight_question_id){$patient->weight=$node['value'];}
            if($node['id']==$gender_question_id){
              foreach($node['answers'] as $answer){
                if ($answer['id']==$node['answer']){$patient->gender=$answer['label'];}
              }
            }
          }
          $patient->save();
          $data_to_save=array(
            'local_medical_case_id'=>$individualData['id'],
            'version_id'=>$individualData['version_id'],
            'created_at'=>$individualData['created_at'],
            'updated_at'=>$individualData['updated_at'],
            'patient_id'=>$patient->id,
            'algorithm_id'=>$individualData['algorithm_id'],
            'algorithm_name'=>$individualData['algorithm_name'],
            'version_name'=>$individualData['version_name']
          );
          MedicalCase::parse_data($data_to_save);
        }
      }
      return response()->json(["data"=>'all cool']);
    }
}
