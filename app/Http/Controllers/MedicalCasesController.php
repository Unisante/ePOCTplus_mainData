<?php

namespace App\Http\Controllers;
use App\MedicalCase;
use App\Answer;
use App\Node;
use App\AnswerType;
use App\MedicalCaseAnswer;
use App\User;
use Illuminate\Http\Request;

class MedicalCasesController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Display all medical Cases
  * @return View,
  * @return $medicalCases
  */
  public function index(){
    $medicalCases=MedicalCase::orderBy('created_at')->get();
    return view('medicalCases.index')->with('medicalCases',$medicalCases);
  }

  /**
  * Show specific medical Case
  * @params $id
  * @return View
  * @return $data
  */
  public function show($id){
    $medicalCase=MedicalCase::find($id);
    $medicalCaseInfo=array();
    foreach($medicalCase->medical_case_answers as $medicalCaseAnswer){
      $data=array(
        "answer"=>Answer::find($medicalCaseAnswer->answer_id),
        "question"=>Node::find($medicalCaseAnswer->node_id),
      );
      array_push($medicalCaseInfo,json_decode(json_encode($data)));
    }

    $data=array(
      'medicalCase'=>$medicalCase,
      'medicalCaseInfo'=>$medicalCaseInfo
    );
    return view('medicalCases.show')->with($data);
  }

  /**
  * Compare between two Medical cases
  * @params firstId
  * @params secondId
  * @return view
  * @return $data
  */
  public function compare($firstId,$secondId){
    $first_medical_case =  MedicalCase::find($firstId);
    $second_medical_case = MedicalCase::find($secondId);

    //medicase details for first medical case
    $medical_case_info=self::detailFind($first_medical_case,"first_case");

    //medicase details for second medical case
    $medical_case_info=self::detailFind($second_medical_case,"second_case",$medical_case_info);
    $data=array(
      'first_medical_case'=>$first_medical_case,
      'second_medical_case'=>$second_medical_case,
      'medical_case_info'=>$medical_case_info,
    );
    return view ('medicalCases.compare')->with($data);
  }

  /**
  * Display an answer of a specific medical case
  * @params $medicalCaseId
  * @params $questionId
  * @return View
  * @return $question
  */
  public function medicalCaseQuestion($medicalCaseId,$questionId){
    $data=array(
      "medicalCase"=>MedicalCase::find($medicalCaseId),
      "question"=>Node::find($questionId),
      "answers"=>Node::find($questionId)->answers,
    );
    return view('medicalCases.question')->with($data);
  }



  /**
  * Find the details of the medical case
  * @params $medical Case
  * @params $label_info
  * @params $medical_case_info
  * @return $medical_case_info
  */
  public function detailFind($medical_case, $label_info, $medical_case_info = array()){
    foreach($medical_case->medical_case_answers as $medicalCaseAnswer){
      $answer=Answer::find($medicalCaseAnswer->answer_id);
      $question=Node::find($medicalCaseAnswer->node_id);
      // $medicalCaseAnswer=$medicalCaseAnswer;
      $medical_case_info[$question->id]["question"] = $question;
      $medical_case_info[$question->id][$label_info] =array(
        "answer"=>$answer,
        "medicalCaseAnswer"=>$medicalCaseAnswer,
      );
    }
    return $medical_case_info;
  }

  /**
  * Show Audit Trail of a particular medical Case
  * @params $medical_case_id
  * @return View
  * @return $medicalCaseAudits
  */
  public function showCaseChanges($medical_case_id){
    $medicalCase=MedicalCase::find($medical_case_id);
    $medicalCase->medical_case_answers;
    $allAudits=array();
    foreach($medicalCase->medical_case_answers as $medicalCaseAnswer){
      $medicalCaseAudit=MedicalCaseAnswer::getAudit($medicalCaseAnswer->id);
      $medicalCaseAuditSize=sizeof(MedicalCaseAnswer::getAudit($medicalCaseAnswer->id));
      if($medicalCaseAuditSize > 0 ){
        foreach($medicalCaseAudit as $audit){
          $auditArray=array(
            "user"=>User::find($audit->user_id)->name,
            "question"=>Node::find($medicalCaseAnswer->node_id)->label,
            "old_value"=>Answer::find($audit->old_values["answer_id"])->label,
            "new_value"=>Answer::find($audit->new_values["answer_id"])->label,
            "url"=>$audit->url,
            "event"=>$audit->event,
            "ip_address"=>$audit->ip_address,
            "created_at"=>$audit->created_at,
          );
          array_push($allAudits,$auditArray);
        }
      }
    }
    $data=array(
      "allAudits"=>$allAudits,
      "medicalCaseId"=>$medical_case_id
    );
    return view ('medicalCases.showCaseChanges')->with($data);
  }
}
