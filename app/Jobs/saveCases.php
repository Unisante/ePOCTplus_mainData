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
use App\PatientConfig;
use App\Answer;
use App\Patient;
use Intervention\Image\ImageManagerStatic as Image;
class SaveCases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filename;
    public $timeout = 300;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename=$filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $study_id='Test';
      $isEligible=true;
      // $zip_path = base_path().'\storage\app\medical_cases_zip';
      // $unparsed_path = base_path().'\storage\app\medical_cases\unparsed_medical_cases';
      // $parsed_path = base_path().'\storage\app\medical_cases\parsed_medical_cases';
      // $consent_path = base_path().'\storage\app\consentFiles';
      $zip_path = base_path().'/storage/app/medical_cases_zip';
      $unparsed_path = base_path().'/storage/app/medical_cases/unparsed_medical_cases';
      $parsed_path = base_path().'/storage/app/medical_cases/parsed_medical_cases';
      $consent_path = base_path().'/storage/app/consentFiles';
      // Madzipper::make($zip_path.'\\'.$this->filename)->extractTo($unparsed_path);
      Madzipper::make($zip_path.'/'.$this->filename)->extractTo($unparsed_path);
      $files = File::allFiles($unparsed_path);
      foreach($files as $path){
        $individualData = json_decode(file_get_contents($path), true);
        $dataForAlgorithm=array("algorithm_id"=> $individualData['algorithm_id'],"version_id"=> $individualData['version_id'],);
        Algorithm::ifOrExists($dataForAlgorithm);
        $patient_key=$individualData['patient'];
        if($patient_key['study_id']== $study_id && $individualData['isEligible']==$isEligible){
          $config= PatientConfig::getConfig($individualData['version_id']);
          $nodes=$individualData['nodes'];
          $gender_answer= Answer::where('medal_c_id',$nodes[$config->gender_question_id]['value'])->first();
          $consent_file_name=$patient_key['uid'] .'_image.jpg';
          if($consent_file_64 = $patient_key['consent_file']){
            $img = Image::make($consent_file_64);
            if(!File::exists($consent_path)) {
              mkdir($consent_path);
            }
            // $img->save($consent_path.'\\'.$consent_file_name);
            $img->save($consent_path.'/'.$consent_file_name);
          }
          $issued_patient=Patient::firstOrCreate(
            [
              "local_patient_id"=>$patient_key['uid']
            ],
            [
            "first_name"=>$nodes[$config->first_name_question_id]['value'],
            "last_name"=>$nodes[$config->last_name_question_id]['value'],
            "birthdate"=>$nodes[$config->birth_date_question_id]['value'],
            "weight"=>$nodes[$config->weight_question_id]['value'],
            "gender"=>$gender_answer->label,
            "group_id"=>$patient_key['group_id'],
            "consent"=>$consent_file_name,
            ]
          );
          $data_to_parse=array(
            'local_medical_case_id'=>$individualData['id'],
            'version_id'=>$individualData['version_id'],
            'created_at'=>$individualData['created_at'],
            'updated_at'=>$individualData['updated_at'],
            'patient_id'=>$issued_patient->id,
            'algorithm_id'=>$individualData['algorithm_id'],
            'nodes'=>$individualData['nodes'],
            'diagnoses'=>$individualData['diagnoses'],
            'consent'=>$individualData['consent'],
            'isEligible'=>$individualData['isEligible']
          );
          MedicalCase::parse_data($data_to_parse);
        }
        if(!File::exists($parsed_path)) {
          mkdir($parsed_path);
        }
        // $new_path=$parsed_path.'\\'.pathinfo($path)['filename'].'.'.pathinfo($path)['extension'];
        $new_path=$parsed_path.'/'.pathinfo($path)['filename'].'.'.pathinfo($path)['extension'];
        $move = File::move($path, $new_path);
      }
    }
}
