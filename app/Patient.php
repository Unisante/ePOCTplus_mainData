<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\MedicalCase;
use App\DuplicatePairs;

class Patient extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;


  function isRedcapFlagged() : bool {
    // TODO : return value of redcap flag in the database
    return false;
  }
  protected $casts = [
    'related_ids' => 'array'
  ];
  protected $guarded = [];

  public function findByUids(){
    $nonMergedPatients= self::where([
      ['merged',0],
    ]
    )->get();
    $duplicateArray=[];
        $nonMergedPatients->each(function($patient)use (&$duplicateArray){
          $keyword=$patient->other_uid;
          if(! $keyword){
            $keyword='nothingToSearch';
          }
          $patientDuplicate=self::where(
            [
              ['other_uid',$patient->local_patient_id],
              ['merged',0],
              ['id','!=' , $patient->id],
            ]
            )
            ->orWhere([
              ['merged',0],
              ['id','!=' , $patient->id]
              ])->whereJsonContains('related_ids',[$keyword])
            ->get()->toArray();
            if($patientDuplicate){
            array_push($patientDuplicate,$patient->toArray());
            array_push($duplicateArray,$patientDuplicate);
          }
        });
    return $duplicateArray;
  }
  public function findByDuplicateKey($duplicateArray){
    $markedPatients=self::where([
      ['duplicate',1],
      ['merged',0]
    ])->get();
    foreach($markedPatients as $patient){
      $patientDuplicate=Patient::where([
        ['last_name',$patient->last_name],
        ['merged',0],
        ['status',0]
      ])
      ->orWhere([
        ['first_name',$patient->first_name],
        ['merged',0],
        ['status',0]
      ])
      ->orWhere([
        ['birthdate',$patient->birthdate],
        ['merged',0],
        ['status',0]
      ])
      ->get()->toArray();
      if(sizeOf($patientDuplicate) > 1 ){
        $pairExist=False;
        collect($duplicateArray)->each(function ($duplicateGroup) use(&$patientDuplicate,&$pairExist){
          $existingIds=[];$incomingIds=[];
          collect($duplicateGroup)->each(function($arrayPatient)use(&$existingIds){
            array_push($existingIds,$arrayPatient['id']);
          });
          collect($patientDuplicate)->each(function($arrayPatient)use(&$incomingIds){
            array_push($incomingIds,$arrayPatient['id']);
          });
          sort($existingIds);sort($incomingIds);
          if ($existingIds===$incomingIds) {
            $pairExist=True;
          }
        });
        if(!$pairExist){
          array_push($duplicateArray,$patientDuplicate);
        }
      }
    }
    return $duplicateArray;
  }
  public function checkForpairs($duplicateArray){
    collect($duplicateArray)->each(function ($duplicatePair, $index) use(&$duplicateArray) {
      $idArray=[];
      collect($duplicatePair)->each(function($pair) use(&$idArray){
        array_push($idArray,$pair['id']);
      });
      if(DuplicatePair::whereJsonContains('pairs',$idArray)->exists()){
        unset($duplicateArray[$index]);
      }
    });
    return $duplicateArray;
  }
  public function keepPairs($pair){
    if(DuplicatePair::whereJsonContains('pairs',$pair)->doesntExist()){
      DuplicatePair::create([
        'pairs'=>$pair
      ]);
    }
  }
  public function combinePairIds($first_patient_ids,$second_patient_ids){
    $first_patient_ids=$first_patient_ids;
    if($first_patient_ids == null){
      $first_patient_ids=[];
    }
    $second_patient_ids=$second_patient_ids;
    if($second_patient_ids == null){
      $second_patient_ids=[];
    }
    $allrelatedIds= array_filter(
      array_merge(
        array_diff($first_patient_ids, $second_patient_ids),
        array_diff($second_patient_ids, $first_patient_ids)
      )
    );
    return $allrelatedIds;
  }
  public function addLocalPatientIds($first_patient_id,$second_patient_ids,$all_related_ids){
    if(! in_array($first_patient_id,$all_related_ids)){
      array_push($all_related_ids,$first_patient_id);
    }
    if(! in_array($second_patient_ids,$all_related_ids)){
      array_push($all_related_ids,$second_patient_ids);
    }
    return $all_related_ids;
  }
  public function addConsentList($first_consent,$second_consent){
    $consent_array=array();
    if($first_consent){
      array_push($consent_array,$first_consent);
    }
    if($second_consent){
      array_push($consent_array,$second_consent);
    }
    $consent = serialize($consent_array);
  }


  /**
  * making a relationship to medicalCase
  * @return Many medical cases
  */
  public function medicalCases()
  {
    return $this->hasMany('App\MedicalCase');
  }
  public function facility(){
    return $this->belongsTo('App\HealthFacility','group_id','group_id');
  }

}
