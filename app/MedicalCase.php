<?php

namespace App;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Madzipper;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Intervention\Image\ImageManagerStatic as Image;
use App\DiagnosisReference;
use App\Diagnosis;
use App\Patient;
use App\MedicalCaseAnswer;
use App\Node;
use App\Algorithm;
use App\PatientConfig;
use App\Answer;
class MedicalCase extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * Checks the json for medical case creation
  * @params $json
  * @params $patient_id
  * @params &$response
  * @return void
  */
  public static function parse_data($data_to_parse){
    $algorithm = Algorithm::where('medal_c_id', $data_to_parse['algorithm_id'])->first();
    $version = Version::where([['medal_c_id', $data_to_parse['version_id']],['algorithm_id',$algorithm->id],])->first();
    $medical_case = self::get_or_create($data_to_parse,$version->id);
    MedicalCaseAnswer::getOrCreate($data_to_parse['nodes'], $medical_case);
    DiagnosisReference::parse_data($medical_case->id,$data_to_parse['diagnoses'],$version->id);
  }

  public static function syncMedicalCases(Request $request){
    $study_id='Test';
    $isEligible=true;
    if($request->file('file')){
      $unparsed_path = base_path().'/storage/medicalCases/unparsed_medical_cases';
      $parsed_path = base_path().'/storage/medicalCases/parsed_medical_cases';
      $consent_path = base_path().'/storage/consentFiles';
      Madzipper::make($request->file('file'))->extractTo($unparsed_path);
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
          // kuna swala la kusave consent file
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
          self::parse_data($data_to_parse);
        }
        if(!File::exists($parsed_path)) {
          mkdir($parsed_path);
        }
        $new_path=$parsed_path.'/'.pathinfo($path)['filename'].'.'.pathinfo($path)['extension'];
        $move = File::move($path, $new_path);
      }
      return response()->json(
        [
          "data_received"=>True,
        ]
      );
    }
    return response()->json(
      [
        "data_received"=>False,
      ]
    );
  }
  /**
  * Create or get medical case
  * @params $local_id
  * @params $patient_id
  * @params $version_id
  *
  * @return $medical_case
  */
  public static function get_or_create($data_to_save,$version_id){
    $medical_case = MedicalCase::firstOrCreate(
      [
        'local_medical_case_id'=>$data_to_save['local_medical_case_id']
      ],
      [
        'patient_id'=>$data_to_save['patient_id'],
        'version_id'=>$version_id,
        'created_at'=>new DateTime($data_to_save['created_at']),
        'updated_at'=>new DateTime($data_to_save['updated_at']),
        'isEligible'=>$data_to_save['isEligible'],
        'consent'=>$data_to_save['consent']
      ]
    );
    return $medical_case;
  }

  /**
  * making a relationship to patient
  * @return one to one patient relationship
  */
  public function patient(){
    return $this->belongsTo('App\Patient');
  }

  /**
  * Make medical case relation
  * @return one to many medical cases retionship
  */
  public function medical_case_answers(){
    return $this->hasMany('App\MedicalCaseAnswer');
  }
  public function diagnoses(){
    return $this->hasMany('App\Diagnosis');
  }

}
