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
Route::get('/medicalcases','MedicalCasesController@index');
Route::get('/medicalCases/{id}','MedicalCasesController@show')->name('MedicalCasesController.show');
Route::get('/medicalCases/compare/{id1}/{id2}','MedicalCasesController@compare');
Route::get('/medicalCases/{medicalCaseId}/question/{questionId}','MedicalCasesController@medicalCaseQuestion')->name('MedicalCasesController.medicalCaseQuestion');
Route::post('/medicalCases/{medicalCaseId}/question/{questionId}/update','medicalCaseAnswersController@update')->name('medicalCaseAnswersController.update');
Route::get('/medicalCase/changes/{id}','MedicalCasesController@showCaseChanges')->name('MedicalCasesController.showCaseChanges');
Route::get('/medicalcases/duplicates','MedicalCasesController@findDuplicates');
Route::post('/medicalCases/duplicates/search','MedicalCasesController@searchDuplicates')->name('MedicalCasesController@searchDuplicates');
Route::post('/medicalCases/duplicates/delete','MedicalCasesController@destroy')->name('MedicalCasesController@destroy');

//for questions
Route::get('/questions','QuestionsController@index')->name('QuestionsController.index');
Route::get('/question/{id}','QuestionsController@show')->name('QuestionsController@show');

//for downloading exports
Route::get('/export-medicalCase-excel','MedicalCasesController@medicalCaseIntoExcel')->name('MedicalCasesController.medicalCaseIntoExcel');
Route::get('/export-medicalCase-csv','MedicalCasesController@medicalCaseIntoCsv')->name('MedicalCasesController.medicalCaseIntoCsv');
Route::get('/export-patient-excel','PatientsController@patientIntoExcel')->name('PatientsController.patientIntoExcel');
Route::get('/export-patient-csv','PatientsController@patientIntoCsv')->name('PatientsController.patientIntoCsv');
Route::get('/export-mainData-csv','PatientsController@allDataIntoExcel')->name('PatientsController.allDataIntoExcel');

