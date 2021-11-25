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

  public function listUnfollowed(){
    $caseFollowUpCollection=new Collection();
    foreach(MedicalCase::where('redcap',false)->get() as $medicalcase){
      $followUp=MedicalCase::makeFollowUp($medicalcase);
        // find the patient related to this followup
      $caseFollowUpCollection->add($followUp);
    }
    return $caseFollowUpCollection;
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
