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
use App\FollowUp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Schema;
use Illuminate\Support\Arr;

class MedicalCase extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  function isRedcapFlagged() : bool {
    // TODO : return value of redcap flag in the database
    return false;
  }

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
  * Make follow up
  * @params $medical_case
  * @params $data
  * @return Void
  */
  public static function makeFollowUp($medical_case){
    // $configurations=json_decode($medical_case->version->configurations->config);
    $date=new DateTime($medical_case->created_at);
    $date->format('Y-m-d H:i:s');
    // $first_name=self::fetchAttribute($medical_case,$configurations->first_name_question_id);
    // $middle_name=self::fetchAttribute($medical_case,$configurations->middle_name_patient_id);
    // $last_name=self::fetchAttribute($medical_case,$configurations->last_name_question_id);
    // $gender=self::fetchAttribute($medical_case,$configurations->gender_question_id);
    // $village_name=self::fetchAttribute($medical_case,$configurations->village_question_id);
    $follow_up=[
      'consultation_id'=>$medical_case->local_medical_case_id,
      'patient_id'=>$medical_case->patient->local_patient_id,
      'hf_id'=>isset($medical_case->group_id)?$medical_case->group_id:null,
      'consultation_date_time'=>$date->format('Y-m-d H:i:s'),
      'group_id'=>1
    ];
    if(! in_array(null,$follow_up) ){
      $follow_up=new FollowUp($medical_case);
      return $follow_up;
    }
    return null;
    // check if the the things in the business rules apply
    // check if the data is already sent to redcap
  }

  /**
  * fetch attribute
  * @params $medical_case
  * @params $medal_c_id
  * @return Void
  */
  public static function fetchAttribute($medical_case,$medal_c_id){
    $node=Node::where('medal_c_id',$medal_c_id)->first();
    $record=$medical_case->medical_case_answers()->where('node_id',$node->id)->first();
    if($record == null){
      return null;
    }
    if($record->answer_id){
      return $record->answer->label;
    }else{
      return $record->value;
    }
  }

  public function listUnfollowed(){
    $caseFollowUpCollection=new Collection();
    foreach(MedicalCase::where('redcap',false)->get() as $medicalcase){
      $followUp=MedicalCase::makeFollowUp($medicalcase);
        // find the patient related to this followup
      $caseFollowUpCollection->add($followUp);
    }
    return $caseFollowUpCollection;
  }

  public function listFollowed(){
    $caseFollowUpCollection=new Collection();
    foreach(MedicalCase::where('redcap',true)->get() as $medicalcase){
      $followUp=MedicalCase::makeFollowUp($medicalcase);
        // find the patient related to this followup
      $caseFollowUpCollection->add($followUp);
    }
    return $caseFollowUpCollection;
  }

  public function getDataCsv($table_name){
    $filename = $table_name.'.csv';
    $patient_data=MedicalCase::all();
    $table_columns=Schema::getColumnListing($table_name);
    $patient_data = Arr::prepend($patient_data->toArray(), $table_columns);
    // file creation
    $file = fopen($filename,"w");
    foreach ($patient_data as $line){
      fputcsv($file,$line);
    }
    fclose($file);
    return $filename;
  }

  /**
  * making a relationship to patient
  * @return one to one patient relationship
  */
  public function patient(){
    return $this->belongsTo('App\Patient');
  }

  /**
  * making a relationship to patient
  * @return one to one patient relationship
  */
  public function version(){
    return $this->belongsTo('App\Version');
  }
  public function facility(){
    return $this->belongsTo('App\HealthFacility','group_id','group_id');
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
  public function diagnosesReferences(){
    return $this->hasMany('App\DiagnosisReference');
  }

}
