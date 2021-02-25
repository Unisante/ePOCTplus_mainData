<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HealthFacility;
use App\MedicalCase;

class FacilitiesController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function index(){
      $facilities=HealthFacility::all();
      foreach($facilities as $facility){
        //find all medical cases related to that health facility
        $latest_case=$facility->medical_cases->sortByDesc('updated_at')->first();
        $number_cases=$facility->medical_cases->count();
        // dd($facility->patients->count());
        $number_patients=$facility->patients->count();
        $facility->number_patients=$number_patients;
        $facility->number_cases=$number_cases;
        $facility->last_case_time=$latest_case;
      }
      return view('facilities.index')->with('facilities',$facilities);
    }
}
