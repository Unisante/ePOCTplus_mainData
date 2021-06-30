<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Madnest\Madzipper\Madzipper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\JsonLog;
use App\Algorithm;
use App\Answer;
use App\HealthFacility;
use Intervention\Image\ImageManagerStatic as Image;
use App\Patient;
use DateTime;
use App\MedicalCase;

class SaveZipCasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $zip_storage_path;
    protected $unparsed_path;
    protected $app_storage_path;
    protected $group_id;
    protected $individualData;
    protected $study_id;
    protected $consent_path;
    protected $parsed_folder;
    protected $failed_folder;
    protected $isEligible;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($zip_storage_path)
    {
        $this->zip_storage_path = $zip_storage_path;
        $this->unparsed_path = base_path().'/storage/app/unparsed_medical_cases';
        $this->app_storage_path = base_path().'/storage/app/';
        $this->failed_folder='failed_medical_cases';
        $this->study_id=env('STUDY_ID');
        $this->consent_path = base_path().'/storage/app/consentFiles';
        $this->parsed_folder='parsed_medical_cases';
        $this->failed_folder='failed_medical_cases';
        $this->isEligible=true;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        var_dump("in the save cases zip");
        try{
          $zipper=new Madzipper();
          $zipper->make($this->app_storage_path.$this->zip_storage_path)->extractTo($this->unparsed_path);
          $counter=1;
          foreach(Storage::allFiles('unparsed_medical_cases') as $case_json){
            var_dump("in reading the files");
            try{
              var_dump("for case: ".$counter." in Zip");
              $counter++;
              $this->individualData = json_decode(Storage::get($case_json), true);
              $this->group_id=(int)$this->individualData['patient']['group_id'];
              var_dump("saving in json logs");
              // saving the json in in the jsonlogs
              JsonLog::firstOrCreate(
                [
                'json_id'=>$this->individualData['id'],
                'group_id'=>$this->group_id,
                ],
                [
                  'case_json'=>json_encode($this->individualData),
                  'zip_name'=>basename($this->zip_storage_path)
                ]
              );
              $patient_key=$this->individualData['patient'];
              var_dump("checking if the patient is this study id and is eligible");
              if($patient_key['study_id']== $this->study_id && $this->individualData['isEligible']==$this->isEligible){
                $dataForAlgorithm=array("algorithm_id"=> $this->individualData['algorithm_id'],"version_id"=> $this->individualData['version_id']);
                $algorithm_n_version=Algorithm::ifOrExists($dataForAlgorithm);
                var_dump("in fetching the algorithm");
                $nodes=$this->individualData['nodes'];
                $gender_answer= Answer::where('medal_c_id',$nodes[$algorithm_n_version["config_data"]->gender_question_id]['answer'])->first();
                $consent_file_name=$patient_key['uid'] .'_image.jpg';
                var_dump("checking and saving health facility ...");
                if(HealthFacility::where('group_id',(int)$patient_key['group_id'])->doesntExist()){
                  $fetchHF=HealthFacility::fetchHealthFacility((int)$patient_key['group_id']);
                }
                var_dump("checking and saving consent...");
                if($consent_file_64 = $patient_key['consent_file']){
                  $img = Image::make($consent_file_64);
                  if(!Storage::exists('consentFiles')) {
                    mkdir($this->consent_path);
                  }
                  $img->save($this->consent_path.'/'.$consent_file_name);
                }
                $other_id='';
                if(property_exists($algorithm_n_version["config_data"],'other_id_patient_id')){
                  $other_id=isset($nodes[$algorithm_n_version["config_data"]->other_id_patient_id])?$nodes[$algorithm_n_version["config_data"]->other_id_patient_id]['value']:null;
                }
                $duplicateConditions=[
                  'first_name'=>isset($nodes[$algorithm_n_version["config_data"]->first_name_question_id])?$nodes[$algorithm_n_version["config_data"]->first_name_question_id]['value']:null,
                  'last_name'=>isset($nodes[$algorithm_n_version["config_data"]->last_name_question_id])?$nodes[$algorithm_n_version["config_data"]->last_name_question_id]['value']:null,
                  'birthdate'=>isset($nodes[$algorithm_n_version["config_data"]->birth_date_question_id])?$nodes[$algorithm_n_version["config_data"]->birth_date_question_id]['value']:null,
                  'middle_name'=>isset($nodes[$algorithm_n_version["config_data"]->middle_name_patient_id])?$nodes[$algorithm_n_version["config_data"]->middle_name_patient_id]['value']:null,
                  'other_id'=>$other_id
                ];
                $duplicateConditions=array_filter($duplicateConditions);
                $duplicate_flag=false;
                $senseDuplicate=Patient::where($duplicateConditions)->exists();

                // $existingPatient=(object)["label"=>'No'];
                // if(array_key_exists($algorithm_n_version["config_data"]->parent_in_study_id,$nodes)){
                // $existingPatient=Answer::where('medal_c_id',$nodes[$algorithm_n_version['config_data']->parent_in_study_id]['id'])->first();
                // if($existingPatient == null){
                //   $existingPatient=(object)["label"=>'No'];
                // }
                // }
                // || $existingPatient->label == 'Yes'
                if($patient_key['other_uid'] || $senseDuplicate ){
                  $duplicate_flag=true;
                }
                var_dump("checking and saving patient...");
                $issued_patient=Patient::firstOrCreate(
                  [
                    "local_patient_id"=>$patient_key['uid']
                  ],
                  [
                  "first_name"=>$nodes[$algorithm_n_version["config_data"]->first_name_question_id]['value'],
                  "middle_name"=>isset($nodes[$algorithm_n_version["config_data"]->middle_name_patient_id])?$nodes[$algorithm_n_version["config_data"]->middle_name_patient_id]['value']:'',
                  "last_name"=>$nodes[$algorithm_n_version["config_data"]->last_name_question_id]['value'],
                  "birthdate"=>$nodes[$algorithm_n_version["config_data"]->birth_date_question_id]['value'],
                  "weight"=>$nodes[$algorithm_n_version["config_data"]->weight_question_id]['value'],
                  "gender"=>$gender_answer->label,
                  "group_id"=>$patient_key['group_id'],
                  "other_group_id"=>$patient_key['other_group_id'],
                  "other_study_id"=>$patient_key['other_study_id'],
                  "other_uid"=>$patient_key['other_uid'],
                  "other_id"=>isset($nodes[$algorithm_n_version["config_data"]->other_id_patient_id])?$nodes[$algorithm_n_version["config_data"]->other_id_patient_id]['value']:'',
                  "consent"=>$consent_file_name,
                  "duplicate"=>$duplicate_flag,
                  "related_ids"=>[$patient_key['other_uid']],
                  "created_at"=>new DateTime($patient_key['created_at']),
                  "updated_at"=>new DateTime($patient_key['updated_at']),
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
                var_dump("parsing a medical case");
                MedicalCase::parse_data($data_to_parse);
                if(Storage::Exists($case_json) && !(Storage::Exists($this->parsed_folder.'/'.basename($case_json)))){
                  Storage::move($case_json, $this->parsed_folder.'/'.basename($case_json));
                }
                // dd(Storage::Exists($case_json));
                Storage::Delete($case_json);
              }else{

                if(Storage::Exists($case_json) && !(Storage::Exists($this->failed_folder.'/'.basename($case_json)))){
                  Storage::move($case_json, $this->failed_folder.'/'.basename($case_json));
                }
                Storage::Delete($case_json);
              }
            }catch(\Exception $e){
              error_log($e);
              Log::info('savecase',  ['error_saving' => $e]);
              if(Storage::Exists($case_json) && !(Storage::Exists($this->failed_folder.'/'.basename($case_json)))){
                Storage::move($case_json, $this->failed_folder.'/'.basename($case_json));
              }
              Storage::Delete($case_json);
            }
          }

          Storage::copy($this->zip_storage_path, 'extracted_cases_zip/'.basename($this->zip_storage_path));
          // Storage::Delete($this->zip_storage_path);
        }catch(\Exception $e){
          error_log($e);
          Log::info('savecase',  ['error_saving' => $e]);
          Storage::copy($this->zip_storage_path, 'failed_cases_zip/'.basename($this->zip_storage_path));
        }
    }
}
