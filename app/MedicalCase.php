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

  public function formatQuestions($case_answers,$common_case_answers){
    // $common_case_answers=[];
    $is_empty=empty($common_case_answers);
    collect($case_answers)->each(function($case_answer)use (&$common_case_answers, &$is_empty){
      // find the node
      $question=$case_answer->node->label;
      $common_case_answers[$case_answer->node_id]=["question"=>$question];

      if($is_empty){
        $case_answer->answer?
        $common_case_answers[$case_answer->node_id]=["case_one"=>$case_answer->answer->label,]:
        $common_case_answers[$case_answer->node_id]=["case_one"=>$case_answer->value,];
      }else{
        $case_answer->answer?
        $common_case_answers[$case_answer->node_id]=["case_two"=>$case_answer->answer->label,]:
        $common_case_answers[$case_answer->node_id]=["case_two"=>$case_answer->value,];
      }
    });
    // dd($common_case_answers);
    // dd("we are in the comparison");
    return $common_case_answers;
  }
  // public function listFacilities(){
  //   $cases=self::all();
  //   $facility_id_arr=[];
  //   dd($cases->load("patient"));
  //   // $facility_id_arr=$cases->each(function($case)use($facility_id_arr){
  //   //   // dd($facility_id_arr);
  //   //   $facility_id=$case->patient->facility->group_id;
  //   //   if ( ! in_array($facility_id,$facility_id_arr)){
  //   //     array_push($facility_id_arr,$facility_id);
  //   //     return $facility_id;
  //   //   }

  //   //   // return null;
  //   // });
  //   dd($facility_id_arr);
  //   dd(self::all());
  //   $caseFollowUpCollection=new Collection();
  //   foreach(MedicalCase::where('redcap',true)->get() as $medicalcase){
  //     $followUp=MedicalCase::makeFollowUp($medicalcase);
  //       // find the patient related to this followup
  //     $caseFollowUpCollection->add($followUp);
  //   }
  //   return $caseFollowUpCollection;
  // }

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
