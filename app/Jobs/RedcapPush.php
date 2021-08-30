<?php

namespace App\Jobs;

use App\FollowUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\MedicalCase;
use App\PatientFollowUp;
use App\Patient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;
use App\Services\RedCapApiService;
use Illuminate\Support\Facades\Log;

class RedcapPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $caseFollowUpArray=array();
      // $patientFollowUpArray=array();
      // MedicalCase::where('redcap',false)->get()->each(function($medicalcase) use (&$patientFollowUpArray,&$caseFollowUpArray){
      //   $followUp=MedicalCase::makeFollowUp($medicalcase);
      //   if($followUp != null){
      //     // if(! $medicalcase->patient->duplicate){
      //       if(! $medicalcase->patient->redcap){
      //         $patientFollowUpArray[]=new PatientFollowUp($medicalcase);
      //       }
      //       $caseFollowUpArray[]=$followUp;
      //     // }
      //   }
      // });

      MedicalCase::where('redcap',false)->get()->each(function($medicalcase) use (&$caseFollowUpArray){
        $followUp=MedicalCase::makeFollowUp($medicalcase);
        //if($followUp != null){
            $caseFollowUpArray[]=$followUp;
        //}
      });
      Log::info(count($caseFollowUpArray));
      $ids_in_redcap=$this->importRedcapFollowUpIds();
      //check for each caseId if it is present in the redcap follow-ups
      collect($caseFollowUpArray)->each(function($followup,$item)use (&$ids_in_redcap, &$caseFollowUpArray){
        if(in_array($followup->getConsultationId(),$ids_in_redcap)){
          MedicalCase::where("local_medical_case_id",$followup->getConsultationId())
          ->update(
            [
              "redcap"=>true
            ]
          );
          unset($caseFollowUpArray[$item]);
        };
      });
      $casefollowUpCollection=collect($caseFollowUpArray);
      $medicalcase_id_list=$this->exportRedcapFollowUps($casefollowUpCollection);
      if($medicalcase_id_list != null){
        collect($medicalcase_id_list)->each(function($case_id){
          MedicalCase::where('local_medical_case_id',$case_id)->update(
            [
              'redcap'=>True
            ]
          );
        });
      }
      Log::info("followup exported");
      // $patientFollowUpCollection=collect($patientFollowUpArray);
      // $casefollowUpCollection=collect($caseFollowUpArray);
      // $redCapApiService = new RedCapApiService();
      // // dd(Config::get('redcap.identifiers.api_url_patient'));
      // $patient_id_list=$this->exportRedCapPatients($patientFollowUpCollection);
      // // $patient_id_list=$redCapApiService->exportPatient($patientFollowUpCollection);
      // if($patient_id_list != null && is_array($patient_id_list)){
      //   if(sizeof($patient_id_list)>0){
      //     foreach($patient_id_list as $local_patient_id){
      //       Patient::where('local_patient_id',$local_patient_id)->update(
      //         [
      //           'redcap'=>True
      //         ]
      //       );
      //     }
      //   }
      // }

      // $medicalcase_id_list=$this->exportRedcapFollowUps($casefollowUpCollection);
      // $medicalcase_id_list=$redCapApiService->exportFollowup($casefollowUpCollection);
      // if($medicalcase_id_list != null && is_array($patient_id_list)){
      //   if(sizeof($medicalcase_id_list) > 0 ){
      //     foreach($medicalcase_id_list as $medicalcase_id){
      //       MedicalCase::where('local_medical_case_id',$medicalcase_id)->update(
      //         [
      //           'redcap'=>True
      //         ]
      //       );
      //     }
      //   }
      // }
    }

    /**
     * @param Collection<Patient> $patients
     * @throws RedCapApiServiceException
    */
    public function exportRedCapPatients(Collection $patients){
      if (count($patients) !== 0) {
        /** @var PatientFollowUp $patient*/
        foreach ($patients as $patient) {
          $datas[$patient->getPatientId()] = [
            Config::get('redcap.identifiers.patient.dyn_pat_study_id_patient') => $patient->getLocalPatientId(),
            Config::get('redcap.identifiers.patient.dyn_pat_first_name') => $patient->getFirstname(),
            Config::get('redcap.identifiers.patient.dyn_pat_middle_name') => $patient->getMiddleName(),
            Config::get('redcap.identifiers.patient.dyn_pat_last_name') => $patient->getLastName(),
            Config::get('redcap.identifiers.patient.dyn_pat_dob') => $patient->getBirthDay(),
            Config::get('redcap.identifiers.patient.dyn_pat_village') => $patient->getVillage(),
            Config::get('redcap.identifiers.patient.dyn_pat_sex') => $patient->getGender(),
            Config::get('redcap.identifiers.patient.dyn_pat_first_name_caregiver') => $patient->getCareGiverFirstName(),
            Config::get('redcap.identifiers.patient.dyn_pat_last_name_caregiver') => $patient->getCareGiverLastName(),
            Config::get('redcap.identifiers.patient.dyn_pat_relationship_child') => $patient->getChildrelation(),
            Config::get('redcap.identifiers.patient.dyn_pat_phone_caregiver') => $patient->getPhoneNumber(),
            Config::get('redcap.identifiers.patient.dyn_pat_phone_owner') => $patient->getPhoneOwner(),
            Config::get('redcap.identifiers.patient.dyn_pat_phone_caregiver_2') => $patient->getOtherPhoneNumber(),
            Config::get('redcap.identifiers.patient.dyn_pat_phone_owner2') => $patient->getOtherOwner(),
            Config::get('redcap.identifiers.patient.complete')=>2,
          ];
          if(in_array('', $datas[$patient->getPatientId()], true) || in_array(null , $datas[$patient->getPatientId()], true)){
            $datas[$patient->getPatientId()][Config::get('redcap.identifiers.patient.complete')]=0;
          }
        }

        $data = array(
          'token' => Config::get('redcap.identifiers.api_token_patient'),
          'content' => 'record',
          'format' => 'json',
          'type' => 'flat',
          'overwriteBehavior' => 'normal',
          'forceAutoNumber' => 'false',
          'data' => json_encode($datas),
          'returnContent' => 'ids',
          'returnFormat' => 'json',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::get('redcap.identifiers.api_url_patient'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
        $output = curl_exec($ch);
        // print $output;
        curl_close($ch);
        return json_decode($output);
      }
    }
    public function exportRedcapFollowUps(Collection $followups){
      if (count($followups) !== 0) {

        foreach ($followups as $followup) {
          /** @var FollowUp $followup*/
          $datas[$followup->getConsultationId()] = [
            // 'redcap_event_name' => Config::get('redcap.identifiers.followup.redcap_event_name'),
            Config::get('redcap.identifiers.followup.dyn_fup_study_id_consultation') => $followup->getConsultationId(),
            Config::get('redcap.identifiers.followup.dyn_fup_study_id_patient') => $followup->getPatientId(),
            Config::get('redcap.identifiers.followup.dyn_fup_firstname') => $followup->getFirstname(),
            Config::get('redcap.identifiers.followup.dyn_fup_middlename') => $followup->getMiddleName(),
            Config::get('redcap.identifiers.followup.dyn_fup_lastname') => $followup->getLastName(),
            Config::get('redcap.identifiers.followup.dyn_fup_sex') => $followup->getGender(),
            Config::get('redcap.identifiers.followup.dyn_fup_birth_date') => $followup->getBirthdate(),
            Config::get('redcap.identifiers.followup.dyn_pat_village') => $followup->getVillage(),
            Config::get('redcap.identifiers.followup.dyn_fup_id_health_facility') => $followup->getFacilityId(),
            Config::get('redcap.identifiers.followup.dyn_fup_date_time_consultation') => $followup->getConsultationDate(),
            Config::get('redcap.identifiers.followup.dyn_fup_first_name_caregiver') => $followup->getCareGiverFirstName(),
            Config::get('redcap.identifiers.followup.dyn_fup_last_name_caregiver') => $followup->getCareGiverLastName(),
            Config::get('redcap.identifiers.followup.dyn_fup_sex_caregiver') => $followup->getCareGiverGender(),
            Config::get('redcap.identifiers.followup.dyn_fup_relationship_child') => $followup->getChildrelation(),
            Config::get('redcap.identifiers.followup.dyn_fup_phone_caregiver') => $followup->getPhoneNumber(),
            Config::get('redcap.identifiers.followup.dyn_fup_phone_owner') => $followup->getPhoneOwner(),
            Config::get('redcap.identifiers.followup.dyn_fup_phone_caregiver_2') => $followup->getOtherPhoneNumber(),
            Config::get('redcap.identifiers.followup.dyn_fup_phone_owner2') => $followup->getOtherOwner(),

            Config::get('redcap.identifiers.followup.dyn_fup_subvillage') => $followup->getSubVillage(),
            Config::get('redcap.identifiers.followup.dyn_fup_address') => $followup->getlandmarkSubVillage(),
            Config::get('redcap.identifiers.followup.dyn_fup_landmark_inst') => $followup->getInstructionForSubVillage(),
            // Config::get('redcap.identifiers.followup.dyn_fup_consultation_id') => $followup->getConsultationId(),
            Config::get('redcap.identifiers.followup.dyn_fup_followup_status') => 1
            // Config::get('redcap.identifiers.followup.identification_complete') => 2,
          ];
          // Log::info('output',  ['data' => $datas]);
          // if(in_array('', $datas[$followup->getConsultationId()], true) || in_array(null , $datas[$followup->getConsultationId()], true)){
          //   $datas[$followup->getConsultationId()][Config::get('redcap.identifiers.followup.identification_complete')]=0;
          // }
        }
        $data = array(
          'token' => Config::get('redcap.identifiers.api_token_followup'),
          'content' => 'record',
          'format' => 'json',
          'type' => 'flat',
          'overwriteBehavior' => 'normal',
          'forceAutoNumber' => 'false',
          'data' => json_encode($datas),
          'returnContent' => 'ids',
          'returnFormat' => 'json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::get('redcap.identifiers.api_url_followup'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
        $output = curl_exec($ch);
        // Log::info('output',  ['output' => $output]);
        curl_close($ch);
        return json_decode($output);
      }
    }
    public function importRedcapFollowUpIds(){
      $data = array(
        'token' => Config::get('redcap.identifiers.api_token_followup'),
        'content' => 'record',
        'format' => 'json',
        'type' => 'flat',
        'csvDelimiter' => '',
        'fields' => array(Config::get('redcap.identifiers.followup.dyn_fup_study_id_consultation')),
        'rawOrLabel' => 'raw',
        'rawOrLabelHeaders' => 'raw',
        'exportCheckboxLabel' => 'false',
        'exportSurveyFields' => 'false',
        'exportDataAccessGroups' => 'false',
        'returnFormat' => 'json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, Config::get('redcap.identifiers.api_url_followup'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
    $response = curl_exec($ch);
    $ids_refactored=[];
    collect(json_decode($response, true))->each(function($id_pair)use (&$ids_refactored){
      $ids_refactored[]=$id_pair[Config::get('redcap.identifiers.followup.dyn_fup_study_id_consultation')];
    });
    curl_close($ch);
      return $ids_refactored;
    }
}
