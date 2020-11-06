<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Diagnosis;
class ManagementReference extends Model
{
  protected $guarded = [];
    public static function store($diagnosis_id,$managements){
      foreach($managements as $management){
        // error_log($diagnosis_id);
        // $issued_management=Management::where('medal_c_id',$management['id'])->first();
        $issued_management=Management::where('medal_c_id',$management['id'])->first();
        // error_log($issued_management->id);
        if($issued_management){
          ManagementReference::firstOrCreate(
            [
              'diagnosis_id'=>$diagnosis_id,
              'management_id'=>$issued_management->id
            ],
            [
              'agreed'=>$management['agreed']
            ]
          );
        }
      }
    }
}
