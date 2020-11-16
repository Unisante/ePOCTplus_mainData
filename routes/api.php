<?php

use Illuminate\Http\Request;
use App\MedicalCase;
use App\MedicalCaseAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Jobs\saveCases;
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
Route::post('sync_medical_cases1',function(Request $request){
  // dd($request->file->originalName);
   $file=Storage::putFile('medical_cases_zip', $request->file);
   saveCases::dispatch($file)->delay(now()->addSeconds(10));
    // dispatch(new saveCases($file));
  //  return response()->json(['response'=>'job received','status'=>200]);

  return MedicalCase::syncMedicalCases($file);
});
