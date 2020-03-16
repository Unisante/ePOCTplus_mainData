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
      return view('patients.showIndividual')->with('patient',$patient);
    }
}
