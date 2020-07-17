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

Route::get('medical_case_answers', function(Request $request) {
    return MedicalCaseAnswer::all();
});

Route::post('sync_medical_cases', function(Request $request) {
    return Patient::parse_json($request);
});

// Route::post('/sync_multi_medical_cases',function(Request $requests){
//   foreach($requests as $request){
//     $data = json_decode(file_get_contents("php://input"), true);
//     foreach($data as $dt){
//       return $dt;
//     }
//     return $data;
//     return response()->json($request['name']);
//   }

// });
