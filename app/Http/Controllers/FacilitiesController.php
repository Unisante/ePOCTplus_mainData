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
      // dd("its me");
      // $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=48.283273,14.295041&sensor=false');
      //   $output= json_decode($geocode);
      //   dd($output);
      //   dd($output->results[0]->formatted_address);
      
      $facilities=HealthFacility::all();
      foreach($facilities as $facility){
        //find all medical cases related to that health facility
        if(! $facility->medical_cases->isEmpty()){
          $latest_case=$facility->medical_cases->sortByDesc('updated_at')->first()->toArray();
          // error_log($latest_case);
          $facility->last_case_time=null;
          // dd($latest_case['updated_at']);
          $number_cases=$facility->medical_cases->count();
          $number_patients=$facility->patients->count();
          $facility->number_patients=$number_patients;
          $facility->number_cases=$number_cases;
          if($latest_case != null){
            $facility->last_case_time=$latest_case['updated_at'];
          }
        }
      }
      return view('facilities.index')->with('facilities',$facilities);
    }
}