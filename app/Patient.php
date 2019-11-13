<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];

    public static function parse_json($request)
    {
//        $json = json_decode(file_get_contents("../public/json/medical.json"));
        $json = $request;
        $patients = $json->patients;

        $response = array();
        $response['patients'] = array();
        $response['medical_cases'] = array();
        foreach ($patients as &$patient_json) {
            $patient_id = $patient_json['id'];

            $patient = self::get_or_create($patient_json['main_data_patient_id'], $patient_json['firstname'], $patient_json['lastname']);
            $response['patients'][$patient_id] = $patient['id'];
            foreach ($patient_json['medicalCases'] as &$medical_case_json){
                MedicalCase::parse_json($medical_case_json, $patient['id'], $response);
            }
        }
        return $response;
    }

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
