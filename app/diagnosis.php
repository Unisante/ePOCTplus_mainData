<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Drug;

class Diagnosis extends Model
{
  protected $guarded = [];

  /**
  * get or store diagnosis
  * @params $nodes
  * @params $version_id
  * @return void
  */
  public static function getOrStore($nodes,$version_id){
    foreach($nodes as $node){
      if(array_key_exists('diagnostic_id',$node) && $node['type']=='FinalDiagnostic'){
        $diagnosis=Diagnosis::firstOrCreate(
          [
            'medal_c_id'=>$node['id'],
            'diagnostic_id'=>$node['diagnostic_id']
          ],
          [
            'label'=>$node['label'],
            'type'=>$node['type'],
            'version_id'=>$version_id
          ]
        );
        Drug::store($node['drugs'],$nodes,$diagnosis->id);
        Management::store($node['managements'],$nodes,$diagnosis->id);
      }
    }
  }

  /**
  * Make drugs relation
  * @return one to many medical cases retionship
  */
  public function drugs(){
    return $this->hasMany('App\Drug');
  }

  /**
  * Make managements relation
  * @return one to many medical cases retionship
  */
  public function managements(){
    return $this->hasMany('App\Management');
  }

  /**
  * Make medical case relation
  * @return one to many medical cases retionship
  */
  public function medical_case(){
    return $this->belongsTo('App\MedicalCase');
  }
}
