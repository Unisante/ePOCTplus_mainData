<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\MedicalCase;
use Illuminate\Support\Facades\Storage;
use File;
use App\Algorithm;
use App\JsonLog;
use App\Answer;
use App\Patient;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Log;
use App\HealthFacility;

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
      try{
      $consent_path = base_path().'/storage/app/consentFiles';
      $parsed_folder='parsed_medical_cases';
      $failed_folder='failed_medical_cases';
      $study_id=env('STUDY_ID');
      $isEligible=true;
      $group_id=(int)$this->individualData['patient']['group_id'];
      // saving the json in in the jsonlogs
      JsonLog::firstOrCreate(
        [
        'json_id'=>$this->individualData['id'],
        'group_id'=>$group_id,
        ],
        [
          'case_json'=>json_encode($this->individualData),

        ]
      );
      $dataForAlgorithm=array("algorithm_id"=> $this->individualData['algorithm_id'],"version_id"=> $this->individualData['version_id'],);
      $algorithm_n_version=Algorithm::ifOrExists($dataForAlgorithm);
      $patient_key=$this->individualData['patient'];
      if($patient_key['study_id']== $study_id && $this->individualData['isEligible']==$isEligible){
        $nodes=$this->individualData['nodes'];
        $gender_answer= Answer::where('medal_c_id',$nodes[$algorithm_n_version["config_data"]->gender_question_id]['answer'])->first();
        $consent_file_name=$patient_key['uid'] .'_image.jpg';
        if(HealthFacility::where('group_id',(int)$patient_key['group_id'])->doesntExist()){
          $fetchHF=HealthFacility::fetchHealthFacility((int)$patient_key['group_id']);
        }
        if($consent_file_64 = $patient_key['consent_file']){
            $img = Image::make($consent_file_64);
            if(!File::exists($consent_path)) {
              mkdir($consent_path);
            }
            $img->save($consent_path.'/'.$consent_file_name);
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
        $existingPatient=(object)["label"=>'No'];
        if(strpos(env("STUDY_ID"), "Dynamic")!== false){
        $existingPatient=Answer::where('medal_c_id',$nodes[$algorithm_n_version['config_data']->parent_in_study_id]['answer'])->first();
        }
        if($patient_key['other_uid'] || $senseDuplicate || $existingPatient->label == 'Yes'){
          $duplicate_flag=true;
        }
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
          "related_ids"=>[$patient_key['other_uid']]
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
      }else{
        if(Storage::Exists($this->filename) && !(Storage::Exists($failed_folder.'/'.basename($this->filename)))){
          Storage::move($this->filename, $failed_folder.'/'.basename($this->filename));
        }
        Storage::Delete($this->filename);
      }
    }catch(\Exception $e){
      error_log($e);
      Log::info('savecase',  ['error_saving' => $e]);
      if(Storage::Exists($this->filename) && !(Storage::Exists($failed_folder.'/'.basename($this->filename)))){
        Storage::move($this->filename, $failed_folder.'/'.basename($this->filename));
      }
      Storage::Delete($this->filename);
    }
  }
  }
