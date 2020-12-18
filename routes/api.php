<?php

use Illuminate\Http\Request;
use App\MedicalCaseAnswer;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Jobs\SaveCase;
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

Route::post('sync_medical_cases',function(Request $request){
  if($request->file){
    $file=Storage::putFile('medical_cases_zip', $request->file);
    $unparsed_path = base_path().'/storage/app/unparsed_medical_cases';
    $parsed_folder='parsed_medical_cases';
    $zipper=new Madzipper();
    $zipper->make($request->file('file'))->extractTo($unparsed_path);
    $filename=basename($file);
    Storage::makeDirectory($parsed_folder);
    dispatch(new SaveCase());
    // foreach(Storage::allFiles('unparsed_medical_cases') as $filename){
    //   $individualData = json_decode(Storage::get($filename), true);
    //   dispatch(new SaveCase($individualData,$filename));
    // }
  //  Storage::delete($file);
    return response()->json(['response'=>'job received','status'=>200]);
  }
  return response()->json(['response'=>'file is null','status'=>400]);
});
