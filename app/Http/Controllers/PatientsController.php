<?php

namespace App\Http\Controllers;
use App\Patient;
use Illuminate\Http\Request;
use Datatables;

class PatientsController extends Controller
{
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
    $data=array(
      'first_patient'=>$first_patient,
      'second_patient'=>$second_patient
    );
    return view('patients.comparePatients')->with($data);
  }
}
