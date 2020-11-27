<?php

namespace App\Http\Controllers;
use App\MedicalCase;
use App\Answer;
use App\Node;
use App\AnswerType;
use App\MedicalCaseAnswer;
use App\User;
use App\DiagnosisReference;
use App\Exports\MedicalCaseExport;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;

class MedicalCasesController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('permission:Merge_Duplicates', ['only' => ['findDuplicates','mergeShow','searchDuplicates','merge']]);
    $this->middleware('permission:Delete_Case',['only'=>['destroy']]);
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
      if($medicalCaseAnswer->answer_id == 0){
        $data=array(
          "answer"=>$medicalCaseAnswer->value,
          "question"=>Node::find($medicalCaseAnswer->node_id),
        );
        array_push($medicalCaseInfo,json_decode(json_encode($data)));
      }else{
        $data=array(
          "answer"=>Answer::find($medicalCaseAnswer->answer_id)->label,
          "question"=>Node::find($medicalCaseAnswer->node_id),
        );
        array_push($medicalCaseInfo,json_decode(json_encode($data)));
      }
    }
    $diagnoses=DiagnosisReference::getDiagnoses($medicalCase->id);
    $data=array(
      'medicalCase'=>$medicalCase,
      'medicalCaseInfo'=>$medicalCaseInfo,
      'diagnoses'=>$diagnoses
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
    $answers=[];
    $question=Node::find($questionId);
    $answer_type=AnswerType::find($question->answer_type_id);
    foreach($question->answers as $answer){
      $answers[$answer->id] = $answer->label;
    }
    $data=array(
      "medicalCase"=>MedicalCase::find($medicalCaseId),
      "question"=>$question,
      "answers"=>$answers,
      "answer_type"=>$answer_type
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
      if($medicalCaseAnswer->answer_id == 0){
        $answer=$medicalCaseAnswer->value;
        $question=Node::find($medicalCaseAnswer->node_id);
        // $medicalCaseAnswer=$medicalCaseAnswer;
        $medical_case_info[$question->id]["question"] = $question;
        $medical_case_info[$question->id][$label_info] =array(
          "answer"=>$answer,
          "medicalCaseAnswer"=>$medicalCaseAnswer,
        );
      }else{
        $answer=Answer::find($medicalCaseAnswer->answer_id);
        $question=Node::find($medicalCaseAnswer->node_id);
        // $medicalCaseAnswer=$medicalCaseAnswer;
        $medical_case_info[$question->id]["question"] = $question;
        $medical_case_info[$question->id][$label_info] =array(
          "answer"=>$answer,
          "medicalCaseAnswer"=>$medicalCaseAnswer,
        );
      }
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

  /**
   * Find duplicates by a certain value
   * @return View
   * @return $catchEachDuplicate
   */
  public function findDuplicates(){
    $duplicates = MedicalCase::select('patient_id','version_id')
    ->groupBy('patient_id','version_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();
    $catchEachDuplicate=array();
    foreach($duplicates as $duplicate){
      $medical_case = MedicalCase::where('patient_id', $duplicate->patient_id)->get();
      array_push($catchEachDuplicate,$medical_case);
    }
    return view('medicalCases.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
  }

  /**
   * Search duplicates
   * @params $request
   * @return view
   */
  public function searchDuplicates(Request $request){
    $columns = Schema::getColumnListing('medical_cases');
    $search_value=$request->search;
    if(in_array($search_value, $columns)){
      $duplicates = MedicalCase::select($search_value)
      ->groupBy($search_value)
      ->havingRaw('COUNT(*) > 1')
      ->get();
      $catchEachDuplicate=array();
      foreach($duplicates as $duplicate){
        $case_duplicates = MedicalCase::where($search_value, $duplicate->$search_value)->get();
        array_push($catchEachDuplicate,$case_duplicates);
      }
      return view('medicalCases.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
    }
    return redirect()->action(
      'MedicalCasesController@findDuplicates'
      );
  }

  /**
  * Delete a particular medical case record
  * @params $request
  * @return View
  */
  public function destroy(Request $request){
    $medical_case=Patient::find($request->medicalc_id);
    if($medical_case->medical_case_answers){
      $medical_case->medical_case_answers->each->delete();
    }
    if($medical_case->delete()){
      return redirect()->action(
        'MedicalCasesController@findDuplicates'
      )->with('status','Row Deleted!');
    }
  }

  public function medicalCaseIntoExcel(){
    return Excel::download(new MedicalCaseExport,'medicalCases.xlsx');
  }
  public function medicalCaseIntoCsv(){
    return Excel::download(new MedicalCaseExport,'medicalCases.csv');
  }
}
