<?php

namespace App;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use App\Diagnosis;
use App\Patient;
use App\MedicalCaseAnswer;
use App\Node;
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
    $algorithm = Algorithm::where([['medal_c_id', $data_to_parse['algorithm_id']],['name',$data_to_parse['algorithm_name']],])->first();
    $version = Version::where([['medal_c_id', $data_to_parse['version_id']],['algorithm_id',$algorithm->id],])->first();
    $medical_case = self::get_or_create($data_to_parse,$version->id);
    MedicalCaseAnswer::getOrCreate($data_to_parse['nodes'], $medical_case);
    error_log('naingia diagnosis');
    dd('not going anywhere from here yet');
    $diagnoses=Diagnosis::parse_data($medical_case,$data_to_parse['nodes'],$data_to_parse['diagnoses']);
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
        'updated_at'=>new DateTime($data_to_save['updated_at']),
        'isEligible'=>$data_to_save['isEligible'],
        'consent'=>$data_to_save['consent']
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
  public function diagnoses(){
    return $this->hasMany('App\Diagnosis');
  }

  public static function getMedicalCase(){
    $medicalCases=MedicalCase::select('id','local_medical_case_id','patient_id','version_id','created_at','updated_at')->get()->toArray();
    return $medicalCases;
  }
}
