<?php

namespace App;

use DateTime;
use App\ConsultationConfig;
use App\node;
use App\PatientConfig;

class PatientFollowUp{
  /** @var String $patient_id*/
  protected $patient_id;
  protected $local_patient_id;
  protected $first_name;
  protected $middle_name;
  protected $last_name;
  protected $birthday;
  protected $gender;
  protected $village;
  protected $group_id;
  protected $caregiver_first_name;
  protected $caregiver_last_name;
  protected $child_relation;
  protected $phone_number;
  protected $phone_owner;
  protected $other_phone_number;
  protected $other_owner;
  protected $complete;
  protected $case;

  public function __construct($medicalcase){
    $patient=$medicalcase->patient;
    $this->case=$medicalcase;
    self::getConfig();
    $date=new DateTime($patient->birthtdate);
    $this->patient_id=$patient->id;
    $this->local_patient_id=$patient->local_patient_id;
    $this->first_name=$patient->first_name;
    $this->middle_name=$patient->middle_name;
    $this->last_name=$patient->last_name;
    $this->birthday=$date->format('Y-m-d');
    if($patient->gender=='Female'){
      $this->gender=1;
    }else{
      $this->gender=2;
    }
  }

  public function getPatientId():int
  {
    return $this->patient_id;
  }
  public function getLocalPatientId():string
  {
    return $this->local_patient_id;
  }
  public function getFirstname():string
  {
    return $this->first_name;
  }
  public function getMiddlename():string
  {
    return $this->middle_name;
  }
  public function getLastName():string
  {
    return $this->last_name;
  }
  public function getBirthDay():string
  {
    return $this->birthday;
  }
  public function getVillage()
  {
    return $this->village;
  }
  public function getGender():string
  {
    return $this->gender;
  }
  public function getCareGiverFirstName()
  {
    return $this->caregiver_first_name;
  }
  public function getCareGiverLastName()
  {
    return $this->caregiver_last_name;
  }
  public function getChildrelation():int
  {
    return $this->child_relation;
  }
  public function getPhoneNumber()
  {
    return $this->phone_number;
  }
  public function getPhoneOwner()
  {
    return $this->phone_owner;
  }
  public function getOtherOwner()
  {
    return $this->other_owner;
  }
  public function getOtherPhoneNumber()
  {
    return $this->other_phone_number;
  }

  // create a function to fetch the variables if they are not yet in the database
  // create a function to fetch this values in the medical case
  private function getConfig(){
    // $configurations_preset=json_encode([
    //   "village"=> 3436,
    //   "caregiver_first_name"=> 3822,
    //   "caregiver_last_name"=> 2176,
    //   "child_relation"=> 2178,
    //   "phone_number"=> 2179,
    //   "other_phone_number"=> 2180
    // ]);
    // if(ConsultationConfig::all()->isEmpty()){
    //   $configurations= ConsultationConfig::create(
    //     [
    //       "version_id"=>$this->case->version_id,
    //     ],
    //     [
    //       "config"=>$configurations_preset
    //     ]
    //   );
    // }

    $config = PatientConfig::where('version_id',$this->case->version_id)->first();
    $config=json_decode($config->config);
    self::setVillage($config);
    self::setCareGiverFirstName($config);
    self::setCareGiverLastName($config);
    self::setChildRelation($config);
    self::setPhoneNumber($config);
    self::setOtherPhoneNumber($config);
    self::setPhoneOwner($config);
    self::setOtherPhoneOwner($config);
  }
  private function findCaseAnswer($medal_c_id){
    $node=Node::where('medal_c_id',$medal_c_id)->first();
    return $this->case->medical_case_answers()->where('node_id',$node->id)->first();
  }
  private function setVillage($config){
    $village_node_id=$config->village_question_id;
    $case_answer=self::findCaseAnswer($village_node_id);
    if($case_answer == null){
      $this->village=null;
    }else{
      $this->village=$case_answer->value;
    }
  }
  private function setCareGiverFirstName($config){
    $caregiver_first_name_node_id=$config->first_name_caregiver_id;
    $case_answer=self::findCaseAnswer($caregiver_first_name_node_id);
    if($case_answer == null){
      $this->caregiver_first_name=null;
    }else{
      $this->caregiver_first_name=$case_answer->value;
    }
  }
  private function setCareGiverLastName($config){
    $caregiver_last_name_node_id=$config->last_name_caregiver_id;
    $case_answer=self::findCaseAnswer($caregiver_last_name_node_id);
    $this->caregiver_last_name=$case_answer->value;
  }
  private function setChildRelation($config){
    $relation=[
      1=>'Mother/Father',
      2=>'Sister/Brother',
      3=>'Aunt/Uncle',
      4=>'Grandparent',
      5=>'Cousin',
      6=>'Neighbour/Friend',
      7=>'Other',
    ];
    $child_relation_node_id=$config->relationship_to_child_id;
    $case_answer=self::findCaseAnswer($child_relation_node_id);
    if(in_array($case_answer->answer->label,$relation)){
      $this->child_relation=array_search($case_answer->answer->label,$relation,true);
    }else{
      $this->child_relation=7;
    }
  }
  private function setPhoneNumber($config){
    $phone_number_node_id=$config->phone_number_caregiver_id;
    $case_answer=self::findCaseAnswer($phone_number_node_id);
    $this->phone_number=$case_answer->value;
  }
  private function setPhoneOwner($config){
    $phone_owner_node_id=$config->phone_number_owner_id;
    $case_answer=self::findCaseAnswer($phone_owner_node_id);
    $this->phone_owner='';
    if($case_answer != null ){
      $this->phone_owner=$case_answer->value;
    }

  }
  private function setOtherPhoneNumber($config){
    $other_phone_number_node_id=$config->other_number_id;
    $case_answer=self::findCaseAnswer($other_phone_number_node_id);
    $this->other_phone_number=$case_answer->value;
  }
  private function setOtherPhoneOwner($config){
    $other_phone_owner_node_id=$config->other_number_owner_id;
    $case_answer=self::findCaseAnswer($other_phone_owner_node_id);
    $this->other_owner='';
    if($case_answer != null){
      $this->other_owner=$case_answer->value;
    }
  }
}
