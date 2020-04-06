<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use App\Patient;
use App\Answer;
use App\User;
use App\Node;
use Illuminate\Http\Request;
use Datatables;
use DB;

class PatientsController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct(){
    $this->middleware('auth');
  }

  /**
  * View all patients
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
  * @params $firstId
  * @params $secondId
  * @return $patients
  */
  public function compare($firstId,$secondId){
    $first_patient =  Patient::find($firstId);
    $second_patient = Patient::find($secondId);
    $data=array(
      'first_patient'=>$first_patient,
      'second_patient'=>$second_patient,
    );
    return view('patients.compare')->with($data);
  }


  /**
  * Find duplicates by a certain value
  * @param $value
  * @return View
  * @return $catchEachDuplicate
  */
  public function findDuplicates(){
    $duplicates = DB::table('patients')
    ->select('first_name','last_name')
    ->groupBy('first_name','last_name')
    ->havingRaw('COUNT(*) > 1')
    ->get();
    $catchEachDuplicate=array();
    foreach($duplicates as $duplicate){
      $users = DB::table('patients')->where('first_name', $duplicate->first_name)->get();
      array_push($catchEachDuplicate,$users);
    }
    return view('patients.duplicateChoice')->with("catchEachDuplicate",$catchEachDuplicate);
  }

  /**
  * Shows merge between patients
  * @params $firstId
  * @params $secondId
  * @return $patients
  */
  public function mergeShow($firstId,$secondId){
    $first_patient =  Patient::find($firstId);
    $second_patient = Patient::find($secondId);
    $data=array(
      'first_patient'=>$first_patient,
      'second_patient'=>$second_patient,
    );
    return view('patients.merge')->with($data);
  }

  /**
  * Mrege between two records
  * @params $request
  * @return PatientsController@findDuplicates
  */
  public function merge(Request $request){
    //creating a new patient
    $hybrid_patient=new Patient;
    $hybrid_patient->first_name=$request->input('first_name');
    $hybrid_patient->last_name=$request->input('last_name');
    $hybrid_patient->save();

    //finding the right medical cases to update
    $first_patient=Patient::find($request->input('firstp_id'));
    $second_patient=Patient::find($request->input('secondp_id'));
    if($first_patient->medicalCases->count()==$request->input('medical_cases')){
      foreach($first_patient->medicalCases as $medical_case){
        $medical_case->patient_id=$hybrid_patient->id;
        $medical_case->save();
    }
    }else{
      foreach($second_patient->medicalCases as $medical_case){
        $medical_case->patient_id=$hybrid_patient->id;
        $medical_case->save();
      }
    }

    //deleting the pre existing patients
    if($first_patient->medicalCases){
      $first_patient->medicalCases->each->delete();
    }
    $first_patient->delete();
    if($second_patient->medicalCases){
      $second_patient->medicalCases->each->delete();
    }
    $second_patient->delete();

    return redirect()->action(
      'PatientsController@findDuplicates'
      )->with('status',' New Row Formed!');

  }

  /**
  * Delete a particular Patient Record
  * @params $request
  * @return View
  */
  public function destroy(Request $request){
    $patient_id=$request->input('patient_id');
    $patient=Patient::find($patient_id);
    if($patient->medicalCases){
      $patient->medicalCases->each->delete();
    }
    if($patient->delete()){
      return redirect()->action(
        'PatientsController@findDuplicates'
      )->with('status','Row Deleted!');
    }
  }

}
