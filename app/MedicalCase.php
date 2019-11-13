<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalCase extends Model
{
    protected $guarded = [];


    public static function parse_json($json, $patient_id)
    {
        $algorithm = Algorithm::get_or_create($json['algorithm_id'], $json['name']);
        $version = Version::get_or_create($json['version'], $algorithm['id']);
        $medical_case = self::get_or_create($patient_id, $version['id']);

        MedicalCaseAnswer::parse_answers($json, $medical_case);
    }

    // TODO Be able to check if the medical case already exists from a real id
    public static function get_or_create($patient_id, $version_id) {
        return MedicalCase::create(['patient_id' => $patient_id, 'version_id' => $version_id]);
    }
}
