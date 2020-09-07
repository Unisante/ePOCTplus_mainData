<?php

use Illuminate\Http\Request;
use App\Patient;
use App\MedicalCaseAnswer;

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

Route::post('sync_medical_cases', function(Request $request) {
    return Patient::parse_json($request);
});

