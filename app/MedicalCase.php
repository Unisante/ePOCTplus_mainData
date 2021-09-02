<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Madzipper;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Collection;
use Schema;
use Illuminate\Support\Arr;

/**
 * Class MedicalCase
 * @package App
 * @mixin Builder
 */
class MedicalCase extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  function isRedcapFlagged() : bool {
    // TODO : return value of redcap flag in the database
    return false;
  }

  /**
  * Make follow up
  * @params $medical_case
  * @params $data
  * @return Void
  */
  public static function makeFollowUp($medical_case){
      $follow_up=new FollowUp($medical_case);
      return $follow_up;
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

  public function getDataCsv($table_name,$fromDate,$toDate){
    ini_set('memory_limit', '4096M');
    ini_set('max_execution_time', '300');
    $filename = $table_name.'.csv';
    // $model_name=Str::studly(Str::singular('medical_cases'));
    $patient_data = collect(DB::table($table_name)->whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->get());
    // $patient_data=MedicalCase::all();
    // dd($patient_data);
    // $fetchData= MedicalCase::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->get();
    $table_columns=Schema::getColumnListing($table_name);
    $patient_data = Arr::prepend($patient_data->toArray(), $table_columns);
    // file creation
    $file = fopen($filename,"w");
    foreach ($patient_data as $line){
      fputcsv($file,(array)$line);
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

  /**
   * Make diagnosis relation
   * @return one to many medical cases retionship
   */
  public function customDiagnoses(){
    return $this->hasMany('App\CustomDiagnosis');
  }

}
