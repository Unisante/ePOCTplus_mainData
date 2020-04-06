<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MedicalCase;
class MedicalCaseAnswersController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct(){
    $this->middleware('auth');
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
    )->with('status','Answer is updated!');
  }
}
