<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DateTime;
use App\Node;
use App\Answer;
use App\DiagnosisReference;
use App\DrugReference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
Use File;

class MedicalCaseAnswer extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
   * Get all audits of one medical case
   * @params $id
   * @return $all
   */
  public static function getAudit($id){
    $medicalCaseAnswer=MedicalCaseAnswer::find($id);
    return $medicalCaseAnswer->audits;
  }

  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
  public function answer(){
    return $this->belongsTo('App\Answer');
  }
  public function node(){
    return $this->belongsTo('App\Node');
  }
}
