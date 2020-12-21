<?php
namespace App;

use DateTime;

class FollowUp{

  /** @var String $consultation_id*/
  protected $consultation_id;
  protected $patient_id;
  protected $hf_id;
  protected $consultation_date_time;
  protected $first_name;
  protected $last_name;
  protected $gender;
  protected $village_name;
  protected $group_id;

  public function __construct($medical_case,$first_name,$last_name,$gender,$village_name){
    $date=new DateTime($medical_case->created_at);
    $date->format('Y-m-d H:i:s');
    $this->consultation_id=$medical_case->local_medical_case_id;
    $this->patient_id=$medical_case->patient->local_patient_id;
    $this->hf_id=isset($medical_case->group_id)?$medical_case->group_id:null;
    $this->consultation_date_time=$date->format('Y-m-d H:i:s');
    $this->first_name=$first_name;
    $this->last_name=$last_name;
    $this->gender=$gender;
    $this->village_name=$village_name;
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
  public function getVillage():String
  {
    return $this->village_name;
  }
}
