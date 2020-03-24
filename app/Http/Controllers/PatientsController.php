<?php

namespace App\Http\Controllers;
use App\Patient;
use App\Answer;
use App\Node;
use Illuminate\Http\Request;
use Datatables;

class PatientsController extends Controller
{
  /**
  * To block any non-authorized user
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
  * View all patients
  *
  * @return $patients
  */
  public function index(){
    $patients=Patient::orderBy('created_at')->get();
    return view('patients.index')->with('patients',$patients);
  }

  /**
  * Show individual Patient
  * @params $id
  * @return $patient
  */
  public function show($id){
    $patient=Patient::find($id);
    return view('patients.showPatient')->with('patient',$patient);
  }

  /**
  * Shows comparison between patients
  * @params $checkedValues
  * @return $patients
  */
  public function compare($checkedValues){
    $patients = explode(",", $checkedValues);
    $first_patient =  Patient::find((int)$patients[0]);
    $second_patient = Patient::find((int)$patients[1]);
    $first_patient->load('medicalCases.medical_case_answers');
    $first_patient_data=array();
    $second_patient_data=array();
    foreach($first_patient->medicalCases as $medicalCase){
      // $case=array();
      foreach($medicalCase->medical_case_answers as $case_answer){
        $question=Node::getQuestion($case_answer->node_id);
        $answer=Answer::getAnswer($case_answer->answer_id);
        $case_info=array("question"=>$question,
        "answer"=>$answer
        );
        array_push($first_patient_data,$case_info);
        // array_push($case,$case_info);
      }
      // array_push($first_patient_data,$case);
    }
    foreach($second_patient->medicalCases as $medicalCase){
      $case=array("case_date"=>$medicalCase->created_at);
      foreach($medicalCase->medical_case_answers as $case_answer){
        $question=Node::getQuestion($case_answer->node_id);
        $answer=Answer::getAnswer($case_answer->answer_id);
        $case_info=array("question"=>$question,
        "answer"=>$answer
        );
        array_push($case,$case_info);
      }
      $case=array(
        "case_date"=>$medicalCase->created_at,
        "cases"=>array_push($second_patient_data,$case),
      );
      array_push($second_patient_data,$case);
    }
    // $app = app();
    // $laravel_object = $app->make('stdClass');
    // $laravel_object->foo = 'bar';
    // dd($laravel_object->foo);
    // dd($first_patient_data);

    $data=array(
      'first_patient'=>$first_patient,
      'second_patient'=>$second_patient,
      'first_patient_data'=>$first_patient_data,
      'second_patient_data'=>$second_patient_data
    );
    return view('patients.comparePatients')->with($data);
  }
}
