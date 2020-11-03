<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Algorithm;
use App\Patient;
use App\Config;
use App\MedicalCase;
class syncMedicalsController extends Controller
{
    public function syncMedicalCases(Request $request){
      $data1=$request->getContent();
      $data=$request->json()->all();
      $study_id='Test';
      $isEligible=true;

      foreach($data as $individualData){
        $dataForAlgorithm=array(
          "algorithm_name"=> $individualData['algorithm_name'],
          "algorithm_id"=> $individualData['algorithm_id'],
          "version_id"=> $individualData['version_id'],
        );
        Algorithm::ifOrExists($dataForAlgorithm);
        // from here on out,evrything you need is either in the database or in the medicalcase json

        $patient_key=$individualData['patient'];
        if($patient_key['study_id']== $study_id && $individualData['isEligible']==$isEligible){
          $patient=new Patient;
          $patient->local_patient_id=$patient_key['uid'];

          //gain the id to search in the nodes
          $config= Config::getConfig($individualData['version_id']);
          $birth_date_question_id=$config->birth_date_question_id;
          $first_name_question_id=$config->first_name_question_id;
          return $first_name_question_id;
          $last_name_question_id=$config->last_name_question_id;
          $weight_question_id=$config->weight_question_id;
          $gender_question_id=$config->gender_question_id;
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
            return $patient;
            return "alooo";
          }
          //find if the patient already exist of create
          $issued_patient = Patient::firstOrCreate(
            [
              'local_patient_id'=>$patient_key['uid'],
            ],
            [
              'first_name'=>$patient->first_name,
              'last_name'=>$patient->last_name,
              'birthdate'=>$patient->birthdate,
              'weight'=>$patient->weight,
              'gender'=>$patient->gender
            ]
          );
          $data_to_parse=array(
            'local_medical_case_id'=>$individualData['id'],
            'version_id'=>$individualData['version_id'],
            'created_at'=>$individualData['created_at'],
            'updated_at'=>$individualData['updated_at'],
            'patient_id'=>$issued_patient->id,
            'algorithm_id'=>$individualData['algorithm_id'],
            'algorithm_name'=>$individualData['algorithm_name'],
            'version_name'=>$individualData['version_name'],
            'nodes'=>$individualData['nodes'],
            'diagnoses'=>$individualData['diagnoses'],
            'consent'=>$individualData['consent'],
            'isEligible'=>$individualData['isEligible']
          );
          MedicalCase::parse_data($data_to_parse);
        }
      }
      return response()->json(["data"=>'all cool']);
    }
}
