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

class SaveCases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filezip;
    protected $zippath;
    // public $tries = 10;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filezip,$zippath)
    {
        $this->filezip=$filezip;
        $this->zippath=$zippath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      error_log("in the handle");
      $study_id='Test';
      $isEligible=true;
      $zip_path = base_path().'/storage/app/medical_cases_zip';
      $unparsed_path = base_path().'/storage/app/unparsed_medical_cases';
      $parsed_path = base_path().'/storage/app/parsed_medical_cases';
      $consent_path = base_path().'/storage/app/consentFiles';
      error_log("before zipper");
      // dd($this->zippath);
      Madzipper::make($this->zippath)->extractTo($unparsed_path);
      error_log("after zipper");
      $parsed_folder='parsed_medical_cases';
      Storage::makeDirectory($parsed_folder);
      // error_log(Storage::allFiles('unparsed_medical_cases')[0]);
      foreach (Storage::allFiles('unparsed_medical_cases') as $filename) {
        error_log($filename);
        error_log("in the loop");
        $file =Storage::get($filename);
        $individualData = json_decode($file, true);
        $dataForAlgorithm=array("algorithm_id"=> $individualData['algorithm_id'],"version_id"=> $individualData['version_id'],);
        error_log("before algorithm");
        $algorithm_n_version=Algorithm::ifOrExists($dataForAlgorithm);
        error_log("after algorithm");
        $patient_key=$individualData['patient'];
        if($patient_key['study_id']== $study_id && $individualData['isEligible']==$isEligible){
          $nodes=$individualData['nodes'];
          $gender_answer= Answer::where('medal_c_id',$nodes[$algorithm_n_version["config_data"]->gender_question_id]['value'])->first();
          $consent_file_name=$patient_key['uid'] .'_image.jpg';
          if($consent_file_64 = $patient_key['consent_file']){
            $img = Image::make($consent_file_64);
            if(!File::exists($consent_path)) {
              mkdir($consent_path);
            }
            $img->save($consent_path.'/'.$consent_file_name);
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
            ]
          );
          error_log("saved a patient");
          $data_to_parse=array(
            'local_medical_case_id'=>$individualData['id'],
            'version_id'=>$individualData['version_id'],
            'created_at'=>$individualData['created_at'],
            'updated_at'=>$individualData['updated_at'],
            'patient_id'=>$issued_patient->id,
            'nodes'=>$individualData['nodes'],
            'diagnoses'=>$individualData['diagnoses'],
            'consent'=>$individualData['consent'],
            'isEligible'=>$individualData['isEligible'],
            'version_id'=>$algorithm_n_version['version_id'],
          );
          error_log("before medical case");
          MedicalCase::parse_data($data_to_parse);
          error_log("after medical case");
        }
        error_log($filename);
        error_log(Storage::Exists($filename));
        error_log($parsed_folder.'/'.basename($filename));
        error_log(!(Storage::Exists($parsed_folder.'/'.basename($filename))));
        if(Storage::Exists($filename) && !(Storage::Exists($parsed_folder.'/'.basename($filename)))){
          Storage::move($filename, $parsed_folder.'/'.basename($filename));
        }
        Storage::Delete($filename);
        error_log("after moving");
      }
      error_log($this->filezip);
      error_log("deleting the zip file");
      Storage::Delete('medical_cases_zip/'.$this->filezip);
      error_log("deleted the zip file");
    }
}
