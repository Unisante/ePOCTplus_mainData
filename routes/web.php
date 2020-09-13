<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/', function () {
    return redirect(route('login'));
});

Route::resource('user','UsersController');
Route::get('/home', 'HomeController@index')->name('home');

//for patient
Route::get('/patients','PatientsController@index')->name('patients.index');;
Route::get('/patient/{id}','PatientsController@show')->name('PatientsController.show');
Route::get('/patients/compare/{id1}/{id2}','PatientsController@compare');
Route::get('/patients/merge/{id1}/{id2}','PatientsController@mergeShow');
Route::get('/patients/duplicates','PatientsController@findDuplicates');
Route::post('/patients/duplicates/search','PatientsController@searchDuplicates')->name('PatientsController@searchDuplicates');
Route::post('/patients/merge','PatientsController@merge');
Route::post('/patients/duplicates/delete','PatientsController@destroy')->name('PatientsController@destroy');

//for medical case
Route::get('/medicalCases','medicalCasesController@index');
Route::get('/medicalCases/{id}','medicalCasesController@show')->name('medicalCasesController.show');
Route::get('/medicalCases/compare/{id1}/{id2}','medicalCasesController@compare');
Route::get('/medicalCases/{medicalCaseId}/question/{questionId}','medicalCasesController@medicalCaseQuestion')->name('medicalCasesController.medicalCaseQuestion');
Route::post('/medicalCases/{medicalCaseId}/question/{questionId}/update','medicalCaseAnswersController@update')->name('medicalCaseAnswersController.update');
Route::get('/medicalCase/changes/{id}','medicalCasesController@showCaseChanges')->name('medicalCasesController.showCaseChanges');
Route::get('/medicalcases/duplicates','MedicalCasesController@findDuplicates');
Route::post('/medicalCases/duplicates/search','MedicalCasesController@searchDuplicates')->name('MedicalCasesController@searchDuplicates');
Route::post('/medicalCases/duplicates/delete','MedicalCasesController@destroy')->name('MedicalCasesController@destroy');

//for questions
Route::get('/questions','QuestionsController@index')->name('QuestionsController.index');
Route::get('/question/{id}','QuestionsController@show')->name('QuestionsController@show');

//for downloading exports
Route::get('/export-medicalCase-excel','MedicalCasesController@medicalCaseIntoExcel')->name('medicalCasesController.medicalCaseIntoExcel');
Route::get('/export-medicalCase-csv','MedicalCasesController@medicalCaseIntoCsv')->name('medicalCasesController.medicalCaseIntoCsv');
Route::get('/export-patient-excel','PatientsController@patientIntoExcel')->name('PatientsController.patientIntoExcel');
Route::get('/export-patient-csv','PatientsController@patientIntoCsv')->name('PatientsController.patientIntoCsv');

