<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Algorithm;
use App\Patient;
use App\Config;
use App\MedicalCase;
use Madzipper;
use File;
use Illuminate\Support\Facades\Storage;

class syncMedicalsController extends Controller
{
    public function syncMedicalCases(Request $request){
      if($request->file('file')){
        $unparsed_path = base_path().'\app\medicalCases\unparsed_medical_cases';
        $parsed_path = base_path().'\app\medicalCases\parsed_medical_cases';
        Madzipper::make($request->file('file'))->extractTo($unparsed_path);
        $files = File::allFiles($unparsed_path);
        foreach($files as $path){
          $jsonString = file_get_contents($path);
          $data = json_decode($jsonString, true);
          // right here is where we start to have fun with the data from each json
          
          if(!File::exists($parsed_path)) {
            mkdir($parsed_path);
          }
          $new_path=$parsed_path.'\\'.pathinfo($path)['filename'].'.'.pathinfo($path)['extension'];
          $move = File::move($path, $new_path);

          return 'done';
        }
        return $files;

        return response()->json($files);
        //read a json file

        return 'it exists';
      }
      return "True";

      // find a way to accept a zipped folder
      //the find a way to extract it

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
