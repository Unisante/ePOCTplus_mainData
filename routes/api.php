<?php

use Illuminate\Http\Request;
use App\MedicalCaseAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Jobs\SaveCases;
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

Route::get('medical_case_answers', function(Request $request) {

    return MedicalCaseAnswer::all();
});

Route::post('sync_medical_cases','syncMedicalsController@syncMedicalCases');
Route::post('queue_sync_medical_cases',function(Request $request){
   $file=Storage::putFile('medical_cases_zip', $request->file);
   $filename=basename($file);
   $zipPath = Storage::path($file);
  //  saveCases::dispatch($filename)->delay(now()->addSeconds(15));
    dispatch(new SaveCases($filename,$zipPath));
   return response()->json(['response'=>'job received','status'=>200]);
  // return response()->json([
  //   "request"=>"received"
  // ]);
});
