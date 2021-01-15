<?php
namespace App;

use DateTime;
use App\ConsultationConfig;
class FollowUp{

  /** @var String $consultation_id*/
  protected $consultation_id;
  protected $patient_id;
  protected $hf_id;
  protected $consultation_date_time;
  protected $first_name;
  protected $last_name;
  protected $gender;
  protected $village;
  protected $group_id;
  protected $caregiver_first_name;
  protected $caregiver_last_name;
  protected $child_relation;
  protected $phone_number;
  protected $other_phone_number;
  protected $case;

  public function __construct($medical_case){
    $this->case=$medical_case;
    self::getConfig();
    $date=new DateTime($medical_case->created_at);
    $date->format('Y-m-d H:i:s');
    $this->consultation_id=$medical_case->local_medical_case_id;
    $this->patient_id=$medical_case->patient->local_patient_id;
    $this->hf_id=isset($medical_case->group_id)?$medical_case->group_id:null;
    $this->consultation_date_time=$date->format('Y-m-d H:i:s');
    $this->group_id=1;

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
    $configurations_preset=json_encode([
      "village"=> 3436,
      "caregiver_first_name"=> 3822,
      "caregiver_last_name"=> 2176,
      "child_relation"=> 2178,
      "phone_number"=> 2179,
      "other_phone_number"=> 2180,
      "caregiver_gender"=>2177
    ]);
    if(ConsultationConfig::all()->isEmpty()){
      $configurations= ConsultationConfig::create(
        [
          "version_id"=>$this->case->version_id,
          "config"=>$configurations_preset
        ]
      );
    }
    $config = ConsultationConfig::where('version_id',$this->case->version_id)->first();
    $config=json_decode($config->config);
    self::setVillage($config);
    self::setCareGiverFirstName($config);
    self::setCareGiverLastName($config);
    self::setChildRelation($config);
    self::setPhoneNumber($config);
    self::setOtherPhoneNumber($config);
  }
  private function findCaseAnswer($medal_c_id){
    $node=Node::where('medal_c_id',$medal_c_id)->first();
    return $this->case->medical_case_answers()->where('node_id',$node->id)->first();
  }
  private function setVillage($config){
    $village_node_id=$config->village;
    $case_answer=self::findCaseAnswer($village_node_id);
    if($case_answer == null){
      $this->village=null;
    }else{
      $this->village=$case_answer->value;
    }
  }
  private function setCareGiverFirstName($config){
    $caregiver_first_name_node_id=$config->caregiver_first_name;
    $case_answer=self::findCaseAnswer($caregiver_first_name_node_id);
    if($case_answer == null){
      $this->caregiver_first_name=null;
    }else{
      $this->caregiver_first_name=$case_answer->value;
    }
  }
  private function setCareGiverLastName($config){
    $caregiver_last_name_node_id=$config->caregiver_last_name;
    $case_answer=self::findCaseAnswer($caregiver_last_name_node_id);
    $this->caregiver_last_name=$case_answer->value;
  }
  private function setChildRelation($config){
    $relation=[
      1=>'Mother/Father',
      2=>'Sister/Brother',
      3=>'Aunt/Uncle',
      4=>'Grandmother/GrandFather',
      5=>'Cousin',
      6=>'Other',
    ];
    $child_relation_node_id=$config->child_relation;
    $case_answer=self::findCaseAnswer($child_relation_node_id);
    if(in_array($case_answer->value,$relation)){
      $this->child_relation=array_search($case_answer->value,$relation,true);
    }else{
      $this->child_relation=6;
    }
  }
  private function setPhoneNumber($config){
    $phone_number_node_id=$config->phone_number;
    $case_answer=self::findCaseAnswer($phone_number_node_id);
    $this->phone_number=$case_answer->value;
  }
  private function setOtherPhoneNumber($config){
    $other_phone_number_node_id=$config->other_phone_number;
    $case_answer=self::findCaseAnswer($other_phone_number_node_id);
    $this->other_phone_number=$case_answer->value;
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
  public function getOtherPhoneNumber()
  {
    return $this->other_phone_number;
  }
}
