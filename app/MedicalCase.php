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
use Illuminate\Support\Facades\Config;
use IU\PHPCap\RedCapProject;
use App\Exceptions\RedCapApiServiceException;
use IU\PHPCap\PhpCapException;
// use Illuminate\Support\Collection;

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

  public function listUnfollowed(){
    $caseFollowUpCollection=new Collection();
    foreach(MedicalCase::where('redcap',false)->get() as $medicalcase){
      $followUp=MedicalCase::makeFollowUp($medicalcase);
        // find the patient related to this followup
      $caseFollowUpCollection->add($followUp);
    }
    return $caseFollowUpCollection;
  }

  public function getAnsweredQuestions($case_id){
    $case=$this::find($case_id);
    $answers_answered=[];
    $case->medical_case_answers->each(function($case_answer) use(&$answers_answered){
      if ( $case_answer->answer || $case_answer->value ){
        // array_push($answers_answered,$case_answer);
        $answers_answered[$case_answer->node_id]=$case_answer;
      }
    });
    return $answers_answered;
  }

  public function removeFollowUp($id){
    $case= $this->find($id);
    $project = new RedCapProject(Config::get('redcap.identifiers.api_url_followup'), Config::get('redcap.identifiers.api_token_followup'));
    $case_id=(array)"0534c96a-b60c-44ea-b8de-380082cf3cef";
    try {
        $project->deleteRecords($case_id);
        $message= "Case Id '{$case_id[0]}' Has been removed from Redcap Folloup";
    } catch (PhpCapException $e) {
        // return  new RedCapApiServiceException("Failed to remove Record : '{$case->local_medical_case_id}'", 0, $e);
        $message= "Case Id '{$case_id[0]}' Has Not been removed from Redcap Folloup, May be its not in redcap Or Network Issues";
    }
    $case->duplicate=1;
    $case->save();
    return $message;
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
  public function diagnoses_references(){
    return $this->hasMany('App\DiagnosisReference');
  }

  /**
   * Make diagnosis relation
   * @return one to many medical cases retionship
   */
  public function custom_diagnoses(){
    return $this->hasMany('App\CustomDiagnosis');
  }

  /**
   * Make activity relation
   * @return one to many medical cases retionship
   */
  public function activities(){
    return $this->hasMany('App\Activity', 'medical_case_id', 'id');
  }

}
