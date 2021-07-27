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

/**
 * Authenticated Device API
 * The first middleware checks the validity of the JWT Token provided in the request
 * The second middleware resolves the device from the token received in the request and updates the
 * last seen timestamp of the devices table
 */
Route::middleware(['auth:api','device.resolve'])->prefix('/v1')->group(function(){
  //Returns the Hub IP and Pin Code of the health facility to which the device is assigned
  Route::get('/health-facility-info','Api\AuthDeviceController@healthFacilityInfo');
  //Fetch the algorithm associated to the health facility associated to the device
  Route::get('/algorithm','Api\AuthDeviceController@algorithm');
  //Upload device information such as Mac Address OS etc... see the DeviceInfoRequest for accepted parameters
  Route::post('/device-info','Api\AuthDeviceController@storeDeviceInfo');
  //Upload medical cases: Work in progress, will have to be related to the appropriate controller
  Route::post('/upload-medical-case',function(Request $request){
    return response()->json(['status' => 200]);
  });
});




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

