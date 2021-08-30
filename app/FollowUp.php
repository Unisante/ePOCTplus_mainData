<?php
namespace App;

use DateTime;
use App\PatientConfig;
use App\Answer;
use App\MedicalCaseAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FollowUp{

  /** @var String $consultation_id*/
  protected $consultation_id;
  protected $patient_id;
  protected $hf_id;
  protected $consultation_date_time;
  protected $first_name;
  protected $middle_name;
  protected $last_name;
  protected $child_gender;
  protected $birthdate;
  protected $village;
  protected $group_id;
  protected $caregiver_first_name;
  protected $caregiver_gender;
  protected $caregiver_last_name;
  protected $child_relation;
  protected $phone_number;
  protected $other_phone_number;
  protected $case;
  protected $phone_owner;
  protected $other_owner;
  protected $subVillage;
  protected $instructionSubVillage;
  protected $landmarkSubVillage;

  public function __construct($medical_case){
    $this->case = $medical_case;
    $date=new DateTime($medical_case->updated_at);
    $date->format('Y-m-d H:i:s');
    $this->consultation_id = $medical_case->local_medical_case_id;
    $this->patient_id = $medical_case->patient->local_patient_id;
    $this->hf_id = Patient::where('local_patient_id', $this->patient_id)->first()->group_id;
    $this->consultation_date_time = $date->format('Y-m-d H:i:s');
    $this->group_id = Patient::where('local_patient_id', $this->patient_id)->first()->group_id;
    $this->getConfig();

  }

  public function getlandmarkSubVillage() {
    return $this->landmarkSubVillage;
  }

  public function getSubVillage() {
    return $this->subVillage;
  }

  public function getInstructionForSubVillage() {
    return $this->instructionSubVillage;
  }

  public function getConsultationId():string
  {
    return $this->consultation_id;
  }
  public function getPatientId():string
  {
    return $this->patient_id;
  }
  public function getFacilityId():int
  {
    return $this->hf_id;
  }
  public function getConsultationDate():string
  {
    return $this->consultation_date_time;
  }
  public function getGroupId():int
  {
    return $this->group_id;
  }
  public function getVillage()
  {
    return $this->village;
  }

  private function getConfig(){
    // $configurations_preset=json_encode([
    //   "village"=> 3436,
    //   "caregiver_first_name"=> 3822,
    //   "caregiver_last_name"=> 2176,
    //   "child_relation"=> 2178,
    //   "phone_number"=> 2179,
    //   "other_phone_number"=> 2180,
    //   "caregiver_gender"=>2177
    // ]);
    // if(ConsultationConfig::all()->isEmpty()){
    //   $configurations= ConsultationConfig::create(
    //     [
    //       "version_id"=>$this->case->version_id,
    //       "config"=>$configurations_preset
    //     ]
    //   );
    // }
    $config = PatientConfig::where('version_id',$this->case->version_id)->first();
    $config=json_decode(json_encode($config->config), FALSE);
    $this->setPatientFirstName($config);
    $this->setPatientMiddleName($config);
    $this->setPatientLastName($config);
    $this->setPatientGender($config);
    $this->setBirthdate($config);
    //$this->setVillage($config);
    $this->setCareGiverFirstName($config);
    $this->setCareGiverLastName($config);
    $this->setCareGiverGender($config);
    $this->setChildRelation($config);
    $this->setPhoneNumber($config);
    $this->setOtherPhoneNumber($config);
    $this->setPhoneOwner($config);
    $this->setOtherPhoneOwner($config);
//  $this->setSubVillage($config);
//  $this->setInstructionSubVillage($config);
//  $this->setLandmarkSubVillage($config);
  }

  private function setLandmarkSubVillage($config) {
    $landmarkSubVillageId = $config->landmark_sub_villag_Id;
    $case_answer = $this->findCaseAnswer($landmarkSubVillageId);
    if($case_answer == null){
      $this->landmarkSubVillage = null;
    }else{
      $this->landmarkSubVillage = $case_answer->value;
    }
  }

  private function setInstructionSubVillage($config) {
    $instructionSubVillageId = $config->instruction_sub_village_id;
    $case_answer = $this->findCaseAnswer($instructionSubVillageId);
    if($case_answer == null){
      $this->instructionSubVillage = null;
    }else{
      $this->instructionSubVillage = $case_answer->value;
    }
  }

  private function setSubVillage($config) {
    $subVillageId = $config->sub_village_id;
    $case_answer = $this->findCaseAnswer($subVillageId);
    if($case_answer == null){
      $this->subVillage = null;
    }else{
      $this->subVillage = $case_answer->value;
    }
  }

  private function findCaseAnswer($medal_c_id){
    Log::debug("medal_c_id".$medal_c_id);
    $node=Node::where('medal_c_id',$medal_c_id)->first();
    if($node == null){
      return null;
    }
    Log::debug("node".$node);
    Log::debug("return".$this->case->medical_case_answers()->where('node_id',$node->id)->first());
    return $this->case->medical_case_answers()->where('node_id',$node->id)->first();
  }
  private function setPatientFirstName($config){
    $this->first_name = Patient::where('local_patient_id', $this->patient_id)->first()->first_name;
  }
  private function setPatientMiddleName($config){
    $this->middle_name = Patient::where('local_patient_id', $this->patient_id)->first()->middle_name;
  }
  private function setPatientLastName($config){
    $this->last_name = Patient::where('local_patient_id', $this->patient_id)->first()->last_name;
  }
  public function setPatientGender($config){
    $relation=[
      1=>'Female',
      2=>'Male',
    ];
    $gender_node_id=$config->gender_patient_id;
    $case_answer=$this->findCaseAnswer($gender_node_id);
    $this->child_gender=1;
    if($case_answer != null){
      $gender_label=$case_answer->answer->label;
      if(in_array($gender_label,$relation)){
        $this->child_gender=array_search(strval($case_answer->answer->label),$relation,true);
      }else{
        $this->child_gender=1;
      }
    }
    // dd($this->child_gender);
  }
  public function setBirthdate($config){
    $this->birthdate = Patient::where('local_patient_id', $this->patient_id)->first()->birthdate;

  }
  private function setVillage($config){
    $village_node_id=$config->village_question_id;
    $case_answer=$this->findCaseAnswer($village_node_id);
    if($case_answer == null){
      $this->village=null;
    }else{
      $this->village=$case_answer->value;
    }
  }
  private function setCareGiverFirstName($config){
    $caregiver_first_name_node_id=$config->first_name_caregiver_id;
    $case_answer=$this->findCaseAnswer($caregiver_first_name_node_id);
    if($case_answer == null){
      $this->caregiver_first_name=null;
    }else{
      $this->caregiver_first_name=$case_answer->value;
    }
  }
  private function setCareGiverLastName($config){
    $caregiver_last_name_node_id=$config->last_name_caregiver_id;
    $case_answer=$this->findCaseAnswer($caregiver_last_name_node_id);
    if($case_answer == null){
      $this->caregiver_last_name=null;
    }else{
      $this->caregiver_last_name=$case_answer->value;
    }
    // $this->caregiver_last_name=$case_answer->value;
  }
  private function setCareGiverGender($config){
    $relation=[
      1=>'Female',
      2=>'Male',
    ];
    $caregiver_gender_node_id=$config->gender_caregiver_id;
    $case_answer=$this->findCaseAnswer($caregiver_gender_node_id);
    $this->caregiver_gender=1;
    if($case_answer != null){
      $gender_label=$case_answer->answer->label;
      if(in_array($gender_label,$relation)){
        $this->caregiver_gender=array_search(strval($case_answer->answer->label),$relation,true);
      }else{
        $this->caregiver_gender=1;
      }
    }
  }
  private function setChildRelation($config){
    $relation=[
      1 =>'Mother/Father',
      2 =>'Sister/Brother',
      3 =>'Aunt/Uncle',
      4 =>'Grandparent',
      5 =>'Cousin',
      6 =>'Neighbour/Friend',
      7 =>'Other',
    ];
    $child_relation_node_id = $config->relationship_to_child_id;
    $case_answer = $this->findCaseAnswer($child_relation_node_id);
    if($case_answer != null){
      $relation_label=$case_answer->answer->label;
      if(in_array($relation_label,$relation)){
        $this->child_relation = array_search(strval($case_answer->answer->label),$relation,true);
      }else{
        $this->child_relation=7;
      }
    }
    else{
      $this->child_relation=7;
    }
    // Log::debug("child relation".$case_answer);
    // if($case_answer->answer){
    //   if(in_array($relation_label,$relation)){
    //     $this->child_relation=array_search(strval($case_answer->answer->label),$relation,true);
    //   }else{
    //     $this->child_relation=7;
    //   }
    // }
    // else{
    //   $this->child_relation=7;
    // }
  }
  private function setPhoneNumber($config){
    $phone_number_node_id = $config->phone_number_caregiver_id;
    $case_answer = $this->findCaseAnswer($phone_number_node_id);
    if($case_answer != null){
      $this->phone_number = $case_answer->value;
    }else{
      $this->phone_number = null;
    }

  }
  private function setPhoneOwner($config){
    $phone_owner_node_id=$config->phone_number_owner_id;
    $case_answer=$this->findCaseAnswer($phone_owner_node_id);
    $this->phone_owner='';
    if($case_answer != null){
      $this->phone_owner=$case_answer->answer->label;
    }
  }
  private function setOtherPhoneNumber($config){
    $other_phone_number_node_id=$config->other_number_id;
    $case_answer=$this->findCaseAnswer($other_phone_number_node_id);
    if($case_answer != null){
      $this->other_phone_number=$case_answer->value;
    }else{
      $this->other_phone_number=null;
    }

  }

  private function setOtherPhoneOwner($config){
    $other_phone_owner_node_id=$config->other_number_owner_id;
    $case_answer=$this->findCaseAnswer($other_phone_owner_node_id);
    $this->other_owner='';
    if($case_answer != null){
      $this->other_owner='';
      if($case_answer->answer != null){
        $this->other_owner=$case_answer->answer->label;
      }
    }
  }

  public function getCareGiverFirstName()
  {
    return $this->caregiver_first_name;
  }
  public function getCareGiverLastName()
  {
    return $this->caregiver_last_name;
  }
  public function getCareGiverGender()
  {
    return $this->caregiver_gender;
  }
  public function getChildrelation():int
  {
    return $this->child_relation;
  }
  public function getPhoneNumber()
  {
    return $this->phone_number;
  }
  public function getOtherPhoneNumber()
  {
    return $this->other_phone_number;
  }
  public function getPhoneOwner()
  {
    return $this->phone_owner;
  }
  public function getOtherOwner()
  {
    return $this->other_owner;
  }
  public function getFirstName()
  {
    return $this->first_name;
  }
  public function getMiddleName()
  {
    return $this->middle_name;
  }
  public function getLastName()
  {
    return $this->last_name;
  }
  public function getGender()
  {
    return $this->child_gender;
  }
  public function getBirthdate(){
    return $this->birthdate;
  }
}
