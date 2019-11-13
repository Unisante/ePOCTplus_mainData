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

        foreach ($patients as &$patient_json) {
            $patient_id = $patient_json['id'];
            // TODO Do a logic to retrieve a patient from local or main data?
//            $patient = Patient::where('medal_c_id', $patient_id)->get();

            $patient = Patient::create(['first_name' => $patient_json['firstname'], 'last_name' => $patient_json['lastname'], 'medal_c_id' => $patient_id]);
            foreach ($patient_json['medicalCases'] as &$medical_case_json){
                MedicalCase::parse_json($medical_case_json, $patient['id']);
            }
        }
    }
}
