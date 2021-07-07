<?php

use Illuminate\Http\Request;
use App\MedicalCaseAnswer;
use App\HealthFacility;
use App\Jobs\ProcessUploadZip;
use Illuminate\Support\Facades\Config;

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

  $path = $request->file('file')->store(Config::get('medal-data.storage.cases_zip_dir'));

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




//DEBUG Routes

Route::post('add_storage_file',function(Request $request){
  if($request->file){
    $file=Storage::putFile('temporary_files', $request->file);
    return Storage::disk('local')->listContents();
    //return response()->json(['data_received'=> true,'status'=>200]);
  }
  return response()->json(['data_received'=> false,'status'=>400]);
});


Route::post('list_storage_files',function(Request $request){
  return Storage::disk('local')->listContents("temporary_files");
});


Route::get('get_storage_file/{filename}',function(Request $request, $filename){
  return Storage::disk('local')->get("temporary_files/" . $filename);
});


Route::post('list_medical_zip',function(Request $request){
  return Storage::disk('local')->listContents("medical_cases_zip");
});

Route::get('get_medical_zip/{filename}',function(Request $request, $filename){
  return Storage::disk('local')->get("medical_cases_zip/" . $filename);
});
