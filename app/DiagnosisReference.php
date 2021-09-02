<?php

namespace App;

use App\Diagnosis;
use Illuminate\Database\Eloquent\Model;
use App\ManagementReference;
use App\DrugReference;
use App\CustomDiagnosis;
use App\Algorithm;
use App\Version;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Management;
class DiagnosisReference extends Model
{
  protected $guarded = [];

  /**
  * get or store diagnosis
  * @params $id
  * @return $diagnoses
  */
  public static function getDiagnoses($id){
    $references=DiagnosisReference::where('medical_case_id',$id)->get();
    $diagnoses=array();
    foreach($references as $reference){
      $diagnosis=Diagnosis::find($reference->diagnosis_id);
      $med_diag=(object)array(
        "agreed"=>$reference->agreed,
        "proposed"=>!$reference->additional,
        "diagnosis_medal_c_id"=>$diagnosis->medal_c_id,
        "label"=>$diagnosis->label,
        "version"=>Version::find($diagnosis->versiod_id)
      );
      array_push($diagnoses,$med_diag);
    }
    return $diagnoses;
  }

  /**
  * Make diagnosis relation
  * @return one to many drub references retionship
  */
  public function drugReferences(){
    return $this->hasMany('App\DrugReference','id','diagnosis_id');
  }

  /**
   * Make diagnoses relation
   * @return one to one drub Diagnosis
   */
  public function diagnoses() {
    return $this->hasOne('App\Diagnosis','id','diagnosis_id');
  }

  /**
  * Make diagnosis relation
  * @return one to many management references retionship
  */
  public function managementReferences(){
    return $this->hasMany('App\ManagementReference','id','diagnosis_id');
  }
}
