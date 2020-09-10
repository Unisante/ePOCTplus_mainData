<?php

namespace App;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Diagnosis;
class MedicalCase extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Checks the json for medical case creation
  * @params $json
  * @params $patient_id
  * @params &$response
  * @return void
  */
  public static function parse_data($data_to_parse){
    $algorithm = Algorithm::getOrCreate($data_to_parse['algorithm_id'], $data_to_parse['algorithm_name']);
    $version = Version::getOrCreate($data_to_parse['version_name'], $algorithm->id,$data_to_parse['version_id']);
    $medical_case = self::get_or_create($data_to_parse,$version->id);
    MedicalCaseAnswer::parse_answers($data_to_parse['nodes'], $medical_case,$algorithm);
    Diagnosis::parse_data($medical_case,$data_to_parse['nodes'],$data_to_parse['diagnoses']);
  }

  /**
  * Create or get medical case
  * @params $local_id
  * @params $patient_id
  * @params $version_id
  *
  * @return $medical_case
  */
  public static function get_or_create($data_to_save,$version_id){
    $medical_case = MedicalCase::firstOrCreate(
      [
        'local_medical_case_id'=>$data_to_save['local_medical_case_id']
      ],
      [
        'patient_id'=>$data_to_save['patient_id'],
        'version_id'=>$version_id,
        'created_at'=>new DateTime($data_to_save['created_at']),
        'updated_at'=>new DateTime($data_to_save['updated_at'])
      ]
    );
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
