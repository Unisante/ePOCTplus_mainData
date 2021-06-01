<?php

use Illuminate\Http\Request;
use App\MedicalCaseAnswer;
use App\HealthFacility;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SaveCase;
use App\Jobs\SaveZipCasesJob;
use App\Jobs\RedcapPush;

use Madnest\Madzipper\Madzipper;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('medical_case_answers', function(Request $request){
    return MedicalCaseAnswer::all();
});

// Route::post('sync_medical_cases','syncMedicalsController@syncMedicalCases');

Route::post('sync_medical_cases_trial',function(Request $request){
  if($request->file){
    $file=Storage::putFile('medical_cases_zip', $request->file);
    $unparsed_path = base_path().'/storage/app/unparsed_medical_cases';
    $parsed_folder='parsed_medical_cases';
    $failed_folder='failed_medical_cases';
    $zipper=new Madzipper();
    $zipper->make($request->file('file'))->extractTo($unparsed_path);
    $filename=basename($file);
    Storage::makeDirectory($parsed_folder);
    Storage::makeDirectory($failed_folder);
    foreach(Storage::allFiles('unparsed_medical_cases') as $filename){
      $individualData = json_decode(Storage::get($filename), true);
      ini_set('maximum_execution_time',300);
        dispatch(new SaveCase($individualData,$filename));
    }
    if(strpos(env("STUDY_ID"), "Dynamic")!== false){
      dispatch(new RedcapPush());
    }
    return response()->json(['data_received'=> true,'status'=>200]);
  }
  return response()->json(['data_received'=> false,'status'=>400]);
});

Route::post('sync_medical_cases',function(Request $request){
  if($request->file){
    //save the zip file and find out the name of the saved zip file.
    $file=Storage::putFile('medical_cases_zip', $request->file);
    // return $file;
    $parsed_folder='parsed_medical_cases';
    $failed_folder='failed_medical_cases';
    Storage::makeDirectory('failed_cases_zip');
    Storage::makeDirectory('extracted_cases_zip');
    Storage::makeDirectory('unparsed_medical_cases');
    Storage::makeDirectory($parsed_folder);
    Storage::makeDirectory($failed_folder);
    error_log('we are in the route');
    dispatch(new SaveZipCasesJob($file));
    if(strpos(env("STUDY_ID"), "Dynamic")!== false){
      dispatch(new RedcapPush());
    }
    return response()->json(['data_received'=> true,'message'=>'Zip File received','status'=>200]);
  }
  return response()->json(['data_received'=> false,'message'=>'No Zip File received','status'=>400]);
});

Route::get('latest_sync/{health_facility_id}',function($health_facility_id){
  if(HealthFacility::where('group_id',$health_facility_id)->doesntExist()){
    return response()->json(
      [
        'status'=>404,
        'response'=>'The facility does not exist in medAL-Data'
      ]
    );
  }
  $facility=HealthFacility::where('group_id',$health_facility_id)->first();
  return response()->json([
    "health_facility_id"=>$facility->group_id,
    "facility_name"=>$facility->facility_name,
    "nb_of_cases_synced"=>$facility->medical_cases->count(),
    "timestamp_json_log"=>$facility->log_cases->sortByDesc('created_at')->pluck('created_at')->first(),
    "total_nb_of_json_log"=>$facility->log_cases->count(),
  ]);
});
