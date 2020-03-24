<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalCase extends Model
{
  protected $guarded = [];

  /**
  * Checks the json for medical case creation
  * @params $json
  * @params $patient_id
  * @params &$response
  * @return void
  */
  public static function parse_json($json, $patient_id, &$response){
    $algorithm = Algorithm::get_or_create($json['algorithm_id'], $json['name']);
    $version = Version::get_or_create($json['version'], $algorithm->id);
    $medical_case = self::get_or_create($json['main_data_medical_case_id'], $patient_id, $version->id);
    $response['medical_cases'][$json['id']] = $medical_case->id;
    MedicalCaseAnswer::parse_answers($json, $medical_case);
  }

  /**
  * Create or get medical case
  * @params $local_id
  * @params $patient_id
  * @params $version_id
  *
  * @return $medical_case
  */
  public static function get_or_create($local_id, $patient_id, $version_id){
    if ($local_id == null) {
      $medical_case = MedicalCase::create(['patient_id' => $patient_id, 'version_id' => $version_id]);
    } else {
      $medical_case = MedicalCase::find($local_id);
    }
    return $medical_case;
  }

  /**
  * making a relationship to patient
  * @return one to one patient relationship
  */
  public function patient(){
    return $this->belongsTo('App\Patient');
  }

  /**
  * Make medical case relation
  * @return one to many medical cases retionship
  */
  public function medical_case_answers(){
    return $this->hasMany('App\MedicalCaseAnswer');
  }
}
