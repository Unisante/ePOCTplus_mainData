<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\User;
use App\MedicalCase;
use App\Patient;
use DB;

class HomeController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Contracts\Support\Renderable
  */
  public function index()
  {
    $case_info = MedicalCase::groupBy('patient_id')->select('patient_id', DB::raw('count(*) as total'))->get();
    $dataPoints=array();
    foreach($case_info as $case){
      $single_data=array("y" => $case->total, "label" => $case->patient_id);
      array_push($dataPoints,$single_data);
    }
    $data=array(
      "currentUser"=>Auth::user(),
      "userCount"=>User::all()->count(),
      "patientCount"=>Patient::all()->count(),
      "mdCases"=>MedicalCase::all()->count(),
      "dataPoints"=>$dataPoints,
    );
    return view("home")->with($data);
  }
}
