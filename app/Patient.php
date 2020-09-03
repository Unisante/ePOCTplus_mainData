<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Response;

class Patient extends Model
{
  protected $guarded = [];

  /*
  * recevieves a json file and makes a save to the database
  * @param  mixed  json file of the patient
  * @return json number of patient and medical cases for now
  */
  public static function old_parse_json($request) {
    $patients = $request->input('patients');
    $response = array();
    $response['patients'] = array();
    $response['medical_cases'] = array();
    foreach ($patients as $key=>&$patient) {
      $main_data_patient_id=isset($patient->main_data_patient_id)? $patient->main_data_patient_id :null;
      $new_patient = self::update_or_create($main_data_patient_id, $patient['firstname'], $patient['lastname']);
      $response['patients'][$key] = $patient['id'];
      foreach ($patient['medicalCases'] as $medical_case_json){
        MedicalCase::get_or_create_case($medical_case_json, $new_patient['id'], $response);
      }
    }
    return $response;
  }

  public static function parse_json($requests) {
      $data = json_decode(file_get_contents("php://input"), true);
      foreach($data as $individualData){

        //create a new patient object
        $patient=new Patient;

        //finding the patient key
        $patient_key=$individualData['patient'];
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
          // if($node['id']==$weight_question_id){$patient->weight=$node['value'];}
          if($node['id']==$gender_question_id){
            foreach($node['answers'] as $answer){
              if ($answer['id']==$node['answer']){$patient->gender=$answer['label'];}
            }
          }
        }
        $patient->save();
      }

  }

  /*
  * Saves or updates the patient
  * @params $id
  * @params $first_name
  * @params $last_name
  * @return  patient object
  */
  public static function update_or_create($local_id, $first_name, $last_name){
    $fields = ['first_name' => $first_name, 'last_name' => $last_name];
    if ($local_id == null) {
      $patient = Patient::create($fields);
    } else {
      $patient = Patient::find($local_id);
      $patient->update($fields);
    }
    return $patient;
  }
}
