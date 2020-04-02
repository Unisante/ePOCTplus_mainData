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
   * Find patient duplicates
   * @return view
   * @return duplicates
   */
  // public function duplicates($value = null){
    // $columns = Schema::getColumnListing('patients');
    // $allPatients=Patient::all();
    // $totalColumnsDuplicate=array();
    // foreach($columns as $column){
    //   $collection = collect($allPatients);
    //   $totalDuplicates=$collection->duplicates($column);
    //   $catchEachDuplicate=array();
    //   foreach($totalDuplicates as $duplicate){
    //     // $users = DB::table('patients')->where($column, $duplicate)->get();
    //     array_push($catchEachDuplicate,$duplicate);
    //   }
    //   array_push($totalColumnsDuplicate,$totalDuplicates);
    // }
    // // foreach($totalColumnsDuplicate){

    // // }
    // return view('patients.duplicates');

  // }

  /**
   * Find duplicates by a certain value
   * @param $value
   * @return View
   * @return $catchEachDuplicate
   */
  public function findDuplicates($value = 'first_name'){
    $findingFIeld=$value;
    $duplicatePatients=Patient::all();
    $collection = collect($duplicatePatients);
    $totalDuplicates=$collection->duplicates($findingFIeld);
    $catchEachDuplicate=array();
    foreach($totalDuplicates as $duplicate){
      $users = DB::table('patients')->where($findingFIeld, $duplicate)->get();
      array_push($catchEachDuplicate,$users);
    }
    return view('patients.duplicateChoice')->with("catchEachDuplicate",$catchEachDuplicate);

  }
}
