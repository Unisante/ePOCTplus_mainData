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
        if(! $facility->patients->isEmpty()){
          $case_count=0;
          $time_array=[];
          foreach($facility->patients() as $patient){
            $patient_case_count=$patient->medicalCases->count();
            $latest_case=$patient->medical_cases->sortByDesc('updated_at')->first()->toArray();
            if($latest_case != null){
            array_push($time_array,$latest_case['updated_at']);
            }
            $case_count=$case_count+$patient_case_count;
          }
          $latest_case=$facility->medical_cases->sortByDesc('updated_at')->first()->toArray();
          $facility->last_case_time=null;
          // $number_cases=$case_count;
          $number_patients=$facility->patients->count();
          $facility->number_patients=$number_patients;
          $facility->number_cases=$case_count;
          $mostRecent= 0;
          foreach($time_array as $date){
            $curDate = strtotime($date);
            if ($curDate > $mostRecent) {
              $mostRecent = $curDate;
            }
          }
          $facility->last_case_time=$mostRecent;
          // if($latest_case != null){
          //   $facility->last_case_time=$latest_case['updated_at'];
          // }
        }
      }
      return view('facilities.index')->with('facilities',$facilities);
    }
}
