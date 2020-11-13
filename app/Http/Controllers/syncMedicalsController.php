<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Algorithm;
use App\Patient;
use App\PatientConfig;
use App\Answer;
use App\MedicalCase;
use Madzipper;
use File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
class syncMedicalsController extends Controller
{
    public function syncMedicalCases(Request $request){
      $study_id='Test';
      $isEligible=true;
      try{
        if($request->file('file')){
          // $unparsed_path = base_path().'\app\medicalCases\unparsed_medical_cases';
          // $parsed_path = base_path().'\app\medicalCases\parsed_medical_cases';
          // $consent_path = base_path().'\app\consentFiles';
          $unparsed_path = base_path().'/storage/medicalCases/unparsed_medical_cases';
          $parsed_path = base_path().'/storage/medicalCases/parsed_medical_cases';
          $consent_path = base_path().'/storage/consentFiles';
          Madzipper::make($request->file('file'))->extractTo($unparsed_path);
          $files = File::allFiles($unparsed_path);
          foreach($files as $path){
            $jsonString = file_get_contents($path);
            $individualData = json_decode($jsonString, true);
            $dataForAlgorithm=array(
              "algorithm_id"=> $individualData['algorithm_id'],
              "version_id"=> $individualData['version_id'],
            );
            Algorithm::ifOrExists($dataForAlgorithm);
            $patient_key=$individualData['patient'];
            if($patient_key['study_id']== $study_id && $individualData['isEligible']==$isEligible){
              $patient=new Patient;
              $patient->local_patient_id=$patient_key['uid'];
              $config= PatientConfig::getConfig($individualData['version_id']);
              $birth_date_question_id=$config->birth_date_question_id;
              $first_name_question_id=$config->first_name_question_id;
              $last_name_question_id=$config->last_name_question_id;
              $weight_question_id=$config->weight_question_id;
              $gender_question_id=$config->gender_question_id;

              if($patient_not_exist=Patient::where('local_patient_id',$patient_key['uid'])->doesntExist()){
                $nodes=$individualData['nodes'];
                $patient->birthdate=$nodes[$birth_date_question_id]['value'];
                $patient->first_name=$nodes[$first_name_question_id]['value'];
                $patient->last_name=$nodes[$last_name_question_id]['value'];
                $patient->weight=$nodes[$weight_question_id]['value'];
                $gender_answer= Answer::where('medal_c_id',$nodes[$gender_question_id]['value'])->first();
                $patient->gender=$gender_answer->label;
                $patient->group_id=$patient_key['group_id'];
                $patient->save();
              }
              $issued_patient=Patient::where('local_patient_id',$patient_key['uid'])->first();
              $consent_exist=$issued_patient->consent;
              if(!$consent_exist){
                $consent_file_name=$issued_patient->local_patient_id .'_image.jpg';
                $issued_patient->consent=$consent_file_name;
                $consent_file_64 = $patient_key['consent_file'];
                $img = Image::make($consent_file_64);
                if(!File::exists($consent_path)) {
                  mkdir($consent_path);
                }
                $img->save($consent_path.'/'.$consent_file_name);
                $issued_patient->save();
              }
              $data_to_parse=array(
                'local_medical_case_id'=>$individualData['id'],
                'version_id'=>$individualData['version_id'],
                'created_at'=>$individualData['created_at'],
                'updated_at'=>$individualData['updated_at'],
                'patient_id'=>$issued_patient->id,
                'algorithm_id'=>$individualData['algorithm_id'],
                'nodes'=>$individualData['nodes'],
                'diagnoses'=>$individualData['diagnoses'],
                'consent'=>$individualData['consent'],
                'isEligible'=>$individualData['isEligible']
              );
              MedicalCase::parse_data($data_to_parse);
            }
            if(!File::exists($parsed_path)) {
              mkdir($parsed_path);
            }
            $new_path=$parsed_path.'/'.pathinfo($path)['filename'].'.'.pathinfo($path)['extension'];
            $move = File::move($path, $new_path);
          }
          return response()->json(
            [
              "data_received"=>True,
            ]
          );
        }else{
          return response()->json(
            [
              "data_received"=>False,
            ]
          );
        }
      }catch(\Exception $e){
        return response()->json([
          "data_received"=>false,
          "response"=>$e
        ]
        );
    }
  }
}
