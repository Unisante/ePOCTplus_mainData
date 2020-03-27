<?php

namespace App\Http\Controllers;
use App\MedicalCase;
use App\Answer;
use App\Node;
use App\AnswerType;
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
  public function medicalCaseQuestion($medicalCaseId,$questionId,$answerId){
    $medicalCase=MedicalCase::find($medicalCaseId);
    $question=Node::getQuestion($questionId);
    $answer=Answer::getAnswer($answerId);
    $data=array(
      "medicalCase"=>$medicalCase,
      "question"=>$question,
      "answer"=>$answer,
    );
    return view('medicalCases.question')->with($data);
  }

  /**
   * Edit Question Answer on a Specific medical case
   * @params $request
   * @params $medicalCaseId
   * @return View
   */
  public function medicalCaseAnswerEdit(Request $request,$medicalCaseId,$answerId){
    $data=request()->validate(array('answer'=>'required',));
    if($answer=Answer::getAnswer($answerId)) {
      $answer->label = $request->input('answer');
      $answer->save();
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
}
