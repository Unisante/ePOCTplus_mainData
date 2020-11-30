<?php

namespace App;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Madzipper;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Intervention\Image\ImageManagerStatic as Image;
use App\DiagnosisReference;
use App\Diagnosis;
use App\Patient;
use App\MedicalCaseAnswer;
use App\Node;
use App\Algorithm;
use App\PatientConfig;
use App\Answer;
use Illuminate\Support\Facades\Storage;

class MedicalCase extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Checks the medical_case.json for medical case creation
  * @params $data_to_parse
  * @return void
  */
  public static function parse_data($data_to_parse){
    $medical_case = self::get_or_create($data_to_parse,$data_to_parse['version_id']);
    MedicalCaseAnswer::getOrCreate($data_to_parse['nodes'], $medical_case);
    DiagnosisReference::parse_data($medical_case->id,$data_to_parse['diagnoses'],$data_to_parse['version_id']);
  }

  /**
  * Create or get medical case
  * @params $data_to_save
  * @params $version_id
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
        'consent'=>$data_to_save['consent'],
        'group_id'=>$data_to_save['group_id'],
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
  * Make medical case answers relation
  * @return one to many medical cases retionship
  */
  public function medical_case_answers(){
    return $this->hasMany('App\MedicalCaseAnswer');
  }

  /**
  * Make diagnosis relation
  * @return one to many medical cases retionship
  */
  public function diagnoses(){
    return $this->hasMany('App\Diagnosis');
  }

}
