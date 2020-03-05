<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];

    /**

    * recevieves a json file and makes a save to the database

    *

    * @param  mixed  json file of the patient

    * @return json number of patient and medical cases for now

    */
    public static function parse_json($request)
    {
        $patients = $request->input('patients');
        $response = array();
        $response['patients'] = array();
        $response['medical_cases'] = array();
        foreach ($patients as $key=>&$patient_json) {
            $main_data_patient_id=isset($patient_json->main_data_patient_id)? $patient_json->main_data_patient_id :null;
            $patient = self::get_or_create($main_data_patient_id, $patient_json['firstname'], $patient_json['lastname']);
            $response['patients'][$key] = $patient['id'];
            foreach ($patient_json['medicalCases'] as &$medical_case_json){
                MedicalCase::parse_json($medical_case_json, $patient['id'], $response);
            }
        }
        return $response;
    }
    /**
        * Dummy method to test if the route works
        *
        *This method will be removed later on
        * 
        * @return  json format of list of patients 
    */
    public static function parse_json_get($request)
    {
        $patient=Patient::all();
        return response()->json(["patients"=>$patient]);
    }

    /**
        * saves the patient
        *
        * 
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
}
