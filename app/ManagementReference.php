<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Diagnosis;
class ManagementReference extends Model
{
  protected $guarded = [];

  /**
  * store managements references
  * @params $diagnosis_id
  * @params $managements
  * @return void
  */
  public static function store($diagnosis_id,$managements){
    foreach($managements as $management){
      $agreed= isset($management['agreed'])?$management['agreed']:false;
      $issued_management=Management::where('medal_c_id',$management['id'])->first();
      if($issued_management){
        ManagementReference::firstOrCreate(
          [
            'diagnosis_id'=>$diagnosis_id,
            'management_id'=>$issued_management->id
          ],
          [
            'agreed'=>$agreed
          ]
        );
      }
    }
  }
}
