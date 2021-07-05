<?php

use Illuminate\Http\Request;
use App\MedicalCaseAnswer;
use App\HealthFacility;
use App\Jobs\ProcessUploadZip;

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

Route::post('sync_medical_cases', function(Request $request) {
  if (!$request->hasFile('file')) {
    return response('Missing attached file', 400);
  }

  $path = $request->file('file')->store(env('CASES_ZIP_DIR'));

  if ($path === false) {
    return response('Unable to save file', 500);
  }

  ProcessUploadZip::dispatch($path);
  return response('Zip file received');
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
