<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\MedicalCase;

class Patient extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;


  function isRedcapFlagged() : bool {
    // TODO : return value of redcap flag in the database
    return false;
  }

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
      $new_patient = self::get_or_create($main_data_patient_id, $patient['firstname'], $patient['lastname']);
      $response['patients'][$key] = $patient['id'];
      foreach ($patient['medicalCases'] as $medical_case_json){
        MedicalCase::get_or_create($medical_case_json, $new_patient['id'], $response);
      }
    }
    return $response;
  }

  public static function parse_json($request) {
    $study_id='Test';
    $data = json_decode(file_get_contents("php://input"), true);
    foreach($data as $individualData){
      $patient=new Patient;
      //finding the patient key
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
        // $patient->save();
        // $mc=MedicalCase::get_or_create($individualData['id'],$patient->id,$individualData['version_id']);
        // $patient->medicalCases->add($mc);
      }
    }
    // // MedicalCase::parse_json();
    return 'all gud';
  }

  /*
  * Saves or updates the patient
  * @params $id
  * @params $first_name
  * @params $last_name
  * @return  patient object
  */
  public static function get_or_create($local_id, $first_name, $last_name){
    $fields = ['first_name' => $first_name, 'last_name' => $last_name];
    if ($local_id == null) {
      $patient = Patient::create($fields);
    } else {
      $patient = Patient::find($local_id);
      $patient->update($fields);
    }
    return $patient;
  }

  /**
  * making a relationship to medicalCase
  * @return Many medical cases
  */
  public function medicalCases()
  {
    return $this->hasMany('App\MedicalCase');
  }

}
