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

Route::post('/user/password/reset','HomeController@forgotPassword')->name('HomeController@forgotPassword');
Route::get('/check_password_reset_token/{id}','HomeController@checkToken')->name('HomeController@checkToken');
Route::post('/reset_user_password','HomeController@makePassword')->name('HomeController@makePassword');

Route::group(['middleware' => ['auth']], function() {
  Route::resource('roles', 'RolesController');
  Route::resource('users','UsersController');
  Route::get('/user/profile',['as'=>'users.profile','uses'=>'UsersController@profile']);
  Route::get('/user/password','UsersController@showChangePassword');
  Route::post('/user/password','UsersController@changePassword')->name('UsersController@changePassword');
  Route::post('/user/reset/{id}','UsersController@resetPassword')->name('UsersController@resetPassword');
  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('roles/removeRole/{id}','RolesController@removeRolePermissionShow');
  Route::post('role/removePerm/{id}','RolesController@removeRolePermission');

  //for patient
  Route::get('/patients','PatientsController@index')->name('Patients.index');;
  Route::get('/patient/{id}','PatientsController@show')->name('PatientsController.show');
  Route::get('/patients/compare/{id1}/{id2}','PatientsController@compare');
  Route::get('/patients/merge/{id1}/{id2}','PatientsController@mergeShow');
  Route::get('/patients/duplicates','PatientsController@findDuplicates');
  Route::post('/patients/duplicates/search','PatientsController@searchDuplicates')->name('PatientsController@searchDuplicates');
  Route::post('/patients/merge','PatientsController@merge');
  Route::post('/patients/duplicates/delete','PatientsController@destroy')->name('PatientsController@destroy');

  //for medical case
  Route::get('/medicalcases','MedicalCasesController@index')->name('MedicalCasesController.index');
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
});






