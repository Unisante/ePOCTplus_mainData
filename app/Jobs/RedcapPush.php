<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\MedicalCase;
use App\PatientFollowUp;
use App\Patient;
use App\Services\RedCapApiService;

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
      $patientFollowUpArray=array();
      foreach(MedicalCase::where('redcap',false)->get() as $medicalcase){
        $followUp=MedicalCase::makeFollowUp($medicalcase);
        if($followUp != null){
          if(! $medicalcase->patient->duplicate){
            if(! $medicalcase->patient->redcap){
              // dd(new PatientFollowUp($medicalcase));
              $patientFollowUpArray[]=new PatientFollowUp($medicalcase);
            }
            $caseFollowUpArray[]=$followUp;
          }
        }
      }
      $patientFollowUpCollection=collect($patientFollowUpArray);
      $casefollowUpCollection=collect($caseFollowUpArray);
      $redCapApiService = new RedCapApiService();
      $patient_id_list=$redCapApiService->exportPatient($patientFollowUpCollection);
      if(sizeof($patient_id_list)>0){
        foreach($patient_id_list as $local_patient_id){
          Patient::where('local_patient_id',$local_patient_id)->update(
            [
              'redcap'=>True
            ]
          );
        }
      }
      $medicalcase_id_list=$redCapApiService->exportFollowup($casefollowUpCollection);
      if(sizeof($medicalcase_id_list) > 0 ){
        foreach($medicalcase_id_list as $medicalcase_id){
          MedicalCase::where('local_medical_case_id',$medicalcase_id)->update(
            [
              'redcap'=>True
            ]
          );
        }
      }
    }
}
