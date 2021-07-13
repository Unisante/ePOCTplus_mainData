<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\MedicalCase;
use App\DuplicatePairs;
use Schema;
use Illuminate\Support\Arr;

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

  /*
  * recevieves a json file and makes a save to the database
  * @param  mixed  json file of the patient
  * @return json number of patient and medical cases for now
  */
  public static function old_parse_json($request) {
    $patients = $request->input('patients');
    $response = array();
    $response['patients'] = array();
    $response['medical_cases'] = array();
    foreach ($patients as $key=>&$patient) {
      $main_data_patient_id=isset($patient->main_data_patient_id)? $patient->main_data_patient_id :null;
      $new_patient = self::get_or_create($main_data_patient_id, $patient['firstname'], $patient['lastname']);
      $response['patients'][$key] = $patient['id'];
      foreach ($patient['medicalCases'] as $medical_case_json){
        MedicalCase::get_or_create($medical_case_json, $new_patient['id'], $response);
      }
    }
    return $response;
  }

  public static function parse_json($request) {
    $study_id='Test';
    $data = json_decode(file_get_contents("php://input"), true);
    foreach($data as $individualData){
      $patient=new Patient;
      //finding the patient key
      $patient_key=$individualData['patient'];
      if($patient_key['study_id']== $study_id){
        $patient->local_patient_id=$patient_key['uid'];
        //gain the id to search in the nodes
        $config=$individualData['config'];
        $birth_date_question_id=$config['basic_questions']['birth_date_question_id'];
        $first_name_question_id=$config['basic_questions']['first_name_question_id'];
        $last_name_question_id=$config['basic_questions']['last_name_question_id'];
        $weight_question_id=$config['basic_questions']['weight_question_id'];
        $gender_question_id=$config['basic_questions']['gender_question_id'];

        //find the values in the node
        $nodes=$individualData['nodes'];
        foreach ($nodes as $node){
          if($node['id']==$birth_date_question_id){$patient->birthdate=$node['value'];}
          if($node['id']==$first_name_question_id){$patient->first_name=$node['value'];}
          if($node['id']==$last_name_question_id){$patient->last_name=$node['value'];}
          if($node['id']==$weight_question_id){$patient->weight=$node['value'];}
          if($node['id']==$gender_question_id){
            foreach($node['answers'] as $answer){
              if ($answer['id']==$node['answer']){$patient->gender=$answer['label'];}
            }
          }
        }
        // $patient->save();
        // $mc=MedicalCase::get_or_create($individualData['id'],$patient->id,$individualData['version_id']);
        // $patient->medicalCases->add($mc);
      }
    }
    // // MedicalCase::parse_json();
    return 'all gud';
  }

  /*
  * Saves or updates the patient
  * @params $id
  * @params $first_name
  * @params $last_name
  * @return  patient object
  */
  public static function get_or_create($local_id, $first_name, $last_name){
    $fields = ['first_name' => $first_name, 'last_name' => $last_name];
    if ($local_id == null) {
      $patient = Patient::create($fields);
    } else {
      $patient = Patient::find($local_id);
      $patient->update($fields);
    }
    return $patient;
  }

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

  public function patientData(){
    $filename = 'patients.csv';
    $patient_data=Patient::all();
    $table_columns=Schema::getColumnListing("patients");
    // for patient table
    $patient_data->each(function($patient){
      return $patient->related_ids=implode(",",$patient->related_ids);
    });
    $patient_data = Arr::prepend($patient_data->toArray(), $table_columns);
    // file creation
    $file = fopen($filename,"w");
    foreach ($patient_data as $line){
      fputcsv($file,$line);
    }
    fclose($file);
    return $filename;
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
