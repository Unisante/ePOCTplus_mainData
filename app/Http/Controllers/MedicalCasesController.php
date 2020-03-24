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
      $answerType=AnswerType::getAnswerType($question->answer_type_id);
      $data=array(
        "answer"=>$answer,
        "question"=>$question,
        'answerType'=>$answerType
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
    $first_medical_case_info=self::detailFind($first_medical_case);
    //medicase details for second medical case
    $second_medical_case_info=self::detailFind($second_medical_case);

    $data=array(
      'first_medical_case'=>$first_medical_case,
      'first_medical_case_info'=>$first_medical_case_info,
      'second_medical_case'=>$second_medical_case,
      'second_medical_case_info'=>$second_medical_case_info,
    );
    return view ('medicalCases.compare')->with($data);
  }

  public function detailFind($medicalCase){
    $medical_case_info=array();
    foreach($medicalCase->medical_case_answers as $medicalCaseAnswer){
      $answer=Answer::getAnswer($medicalCaseAnswer->answer_id);
      $question=Node::getQuestion($medicalCaseAnswer->node_id);
      $answerType=AnswerType::getAnswerType($question->answer_type_id);
      $data=array(
        "answer"=>$answer,
        "question"=>$question,
        'answerType'=>$answerType
      );
      array_push($medical_case_info,json_decode(json_encode($data)));
    }
    return $medical_case_info;
  }
}
