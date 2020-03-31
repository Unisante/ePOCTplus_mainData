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
  *
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
      $answer=Answer::getAnswer($medicalCaseAnswer->answer_id);
      $question=Node::getQuestion($medicalCaseAnswer->node_id);
      $data=array(
        "answer"=>$answer,
        "question"=>$question,
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
  * Compare between two Medical Cases
  * @ids
  * @return view
  * @return $data
  */
  public function compare($ids){
    $medicalCases = explode(",", $ids);
    $first_medical_case =  MedicalCase::find((int)$medicalCases[0]);
    $second_medical_case = MedicalCase::find((int)$medicalCases[1]);

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
    $medicalCase=MedicalCase::find($medicalCaseId);
    $question=Node::getQuestion($questionId);
    $answers=$question->answers;
    $data=array(
      "medicalCase"=>$medicalCase,
      "question"=>$question,
      "answers"=>$answers,
    );
    return view('medicalCases.question')->with($data);
  }

  /**
  * Edit Question Answer on a Specific medical case
  * @params $request
  * @params $medicalCaseId
  * @params $questionId
  * @return View
  */
  public function medicalCaseAnswerUpdate(Request $request,$medicalCaseId,$questionId){
    $data=request()->validate(array('answer'=>'required',));
    $medicalCase=MedicalCase::find($medicalCaseId);
    $medicalCaseAnswer=$medicalCase->medical_case_answers->where('node_id', $questionId)->first();
    if($medicalCaseAnswer) {
      $medicalCaseAnswer->answer_id = (int)$request->input('answer');
      $medicalCaseAnswer->save();
    }else{
      redirect()->back()->with('status', 'Something went wrong.');
    }
    return redirect()->action(
      'medicalCasesController@show', ['id' => $medicalCaseId]
      )->with('status','Question Answer Edited');
  }

  /**
  * Find the details of the medical Case
  * @params $medical Case
  * @params $label_info
  * @params $medical_case_info
  * @return $medical_case_info
  */
  public function detailFind($medicalCase, $label_info, $medical_case_info = array()){
    foreach($medicalCase->medical_case_answers as $medicalCaseAnswer){
      $answer=Answer::getAnswer($medicalCaseAnswer->answer_id);
      $question=Node::getQuestion($medicalCaseAnswer->node_id);
      $medicalCaseAnswer=$medicalCaseAnswer;
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
  * @params $medicalCaseId
  * @return View
  * @return $medicalCaseAudits
  */
  public function showCaseChanges($medicalCaseId){
    $medicalCase=MedicalCase::find($medicalCaseId);
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
    return view ('medicalCases.showCaseChanges')->with("allAudits",$allAudits);
  }
}
