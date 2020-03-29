<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Patient extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];
  /*
  * recevieves a json file and makes a save to the database
  * @param  mixed  json file of the patient
  * @return json number of patient and medical cases for now
  */
  public static function parse_json($request) {
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
    return $this->hasMany('App\medicalCase');
  }
}
