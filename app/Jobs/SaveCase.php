<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\MedicalCase;
use Illuminate\Support\Facades\Storage;
use Madzipper;
use File;
use App\Algorithm;
use App\Answer;
use App\Patient;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Log;
use App\Services\RedCapApiService;

class SaveCase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $individualData;
    protected $filename;
    public $tries = 1;

    /**
     * Create a new job instance.
     * @params $individualData
     * @params $filename
     * @return void
     */
    public function __construct($individualData,$filename)
    {
        $this->individualData=$individualData;
        $this->filename=$filename;
    }

    /**
     * save a medical case.
     *
     * @return void
     */
    public function handle()
    {
      $consent_path = base_path().'/storage/app/consentFiles';
      $parsed_folder='parsed_medical_cases';
      $study_id="Dynamic Tanzania";
      $isEligible=true;
      // foreach(Storage::allFiles('unparsed_medical_cases') as $this->filename){
        $this->individualData = json_decode(Storage::get($this->filename), true);
        $dataForAlgorithm=array("algorithm_id"=> $this->individualData['algorithm_id'],"version_id"=> $this->individualData['version_id'],);
        $algorithm_n_version=Algorithm::ifOrExists($dataForAlgorithm);
        $patient_key=$this->individualData['patient'];
        if($patient_key['study_id']== $study_id && $this->individualData['isEligible']==$isEligible){
          $nodes=$this->individualData['nodes'];
          $gender_answer= Answer::where('medal_c_id',$nodes[$algorithm_n_version["config_data"]->gender_question_id]['answer'])->first();
          $consent_file_name=$patient_key['uid'] .'_image.jpg';
          if($consent_file_64 = $patient_key['consent_file']){
              $img = Image::make($consent_file_64);
              if(!File::exists($consent_path)) {
                mkdir($consent_path);
              }
              $img->save($consent_path.'/'.$consent_file_name);
          }
          $duplicateConditions=[
            'first_name'=>$nodes[$algorithm_n_version["config_data"]->first_name_question_id]['value'],
            'last_name'=>$nodes[$algorithm_n_version["config_data"]->last_name_question_id]['value'],
            'birthdate'=>$nodes[$algorithm_n_version["config_data"]->birth_date_question_id]['value']
          ];
          $duplicate_flag=false;
          $senseDuplicate=Patient::where($duplicateConditions)->exists();
          if($senseDuplicate){
            $duplicate_flag=true;
          }
          $issued_patient=Patient::firstOrCreate(
            [
              "local_patient_id"=>$patient_key['uid']
            ],
            [
            "first_name"=>$nodes[$algorithm_n_version["config_data"]->first_name_question_id]['value'],
            "last_name"=>$nodes[$algorithm_n_version["config_data"]->last_name_question_id]['value'],
            "birthdate"=>$nodes[$algorithm_n_version["config_data"]->birth_date_question_id]['value'],
            "weight"=>$nodes[$algorithm_n_version["config_data"]->weight_question_id]['value'],
            "gender"=>$gender_answer->label,
            "group_id"=>$patient_key['group_id'],
            "consent"=>$consent_file_name,
            "duplicate"=>$duplicate_flag
            ]
          );
          $data_to_parse=array(
            'local_medical_case_id'=>$this->individualData['id'],
            'version_id'=>$this->individualData['version_id'],
            'created_at'=>$this->individualData['created_at'],
            'updated_at'=>$this->individualData['updated_at'],
            'patient_id'=>$issued_patient->id,
            'nodes'=>$this->individualData['nodes'],
            'diagnoses'=>$this->individualData['diagnoses'],
            'consent'=>$this->individualData['consent'],
            'isEligible'=>$this->individualData['isEligible'],
            'version_id'=>$algorithm_n_version['version_id'],
            'group_id'=>$patient_key['group_id'],
            'check-config'=>$algorithm_n_version["config_data"]
          );
          MedicalCase::parse_data($data_to_parse);
          if(Storage::Exists($this->filename) && !(Storage::Exists($parsed_folder.'/'.basename($this->filename)))){
              Storage::move($this->filename, $parsed_folder.'/'.basename($this->filename));
          }
          Storage::Delete($this->filename);
        }
      // }

      // $caseFollowUpArray=array();
      // $patientFollowUpArray=array();
      // foreach(MedicalCase::where('redcap',false)->get() as $medicalcase){
      //   $followUp=MedicalCase::makeFollowUp($medicalcase);
      //   if($followUp != null){
      //     // find the patient related to this followup
      //     if(! $medicalcase->patient->duplicate){
      //       $patientFollowUpArray[]=$medicalcase->patient;
      //       $caseFollowUpArray[]=$followUp;
      //     }
      //   }
      // }
      // $patientFollowUpArray=collect($patientFollowUpArray);
      // $casefollowUpCollection=collect($caseFollowUpArray);
      // $redCapApiService = new RedCapApiService();
      // $medicalcase_id_list=$redCapApiService->exportFollowup($casefollowUpCollection);
      // if(sizeof($medicalcase_id_list) > 0 ){
      //   foreach($medicalcase_id_list as $medicalcase_id){
      //     MedicalCase::where('local_medical_case_id',$medicalcase_id)->update(
      //       [
      //         'redcap'=>True
      //       ]
      //     );
      //   }
      // }
    }
}
