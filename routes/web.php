<?php

use Illuminate\Http\Request;

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


  //for devices
 // Route::get('/devices','DevicesController@index')->name('Devices.index');;


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
  Route::post('/medicalCases/{medicalCaseId}/question/{questionId}/update','MedicalCaseAnswersController@update')->name('MedicalCaseAnswersController.update');
  Route::get('/medicalCase/changes/{id}','MedicalCasesController@showCaseChanges')->name('MedicalCasesController.showCaseChanges');
  Route::get('/medicalcases/duplicates','MedicalCasesController@findDuplicates');
  Route::post('/medicalCases/duplicates/search','MedicalCasesController@searchDuplicates')->name('MedicalCasesController@searchDuplicates');
  Route::post('/medicalCases/duplicates/delete','MedicalCasesController@destroy')->name('MedicalCasesController@destroy');
  Route::get('/followUp/delayed','MedicalCasesController@followUpDelayed');
  Route::get('/followUp/done','MedicalCasesController@followUpDone');
  //for questions
  Route::get('/questions','QuestionsController@index')->name('QuestionsController.index');
  Route::get('/question/{id}','QuestionsController@show')->name('QuestionsController@show');

  //for facilities
  Route::get('/facilities/index','FacilitiesController@index')->name('FacilitiesController.index');


  //for health facilities
  Route::resource('health-facilities','HealthFacilityController')->only([
    'index',
    'store',
    'update',
    'destroy'
  ]);
  //Device Management in the context of Health Facilities
  Route::get('health-facilities/{health_facility}/manage-devices',"HealthFacilityController@manageDevices");
  Route::post('health-facilities/{health_facility}/assign-device/{device}',"HealthFacilityController@assignDevice");
  Route::post('health-facilities/{health_facility}/unassign-device/{device}',"HealthFacilityController@unassignDevice");
  //Algorithms Management in the context of Health Facilities
  Route::get('health-facilities/{health_facility}/manage-algorithms',"HealthFacilityController@manageAlgorithms");
  Route::get('health-facilities/versions/{algorithm_id}',"HealthFacilityController@versions");
  Route::post('health-facilities/{health_facility}/assign-version/{version_id}',"HealthFacilityController@assignVersion");
  //for Devices
  Route::resource('devices','DeviceController');
  //for downloading exports
  // Route::get('/export-medicalCase-excel','MedicalCasesController@medicalCaseIntoExcel')->name('MedicalCasesController.medicalCaseIntoExcel');
  // Route::get('/export-medicalCase-csv','MedicalCasesController@medicalCaseIntoCsv')->name('MedicalCasesController.medicalCaseIntoCsv');
  // Route::get('/export-patient-excel','PatientsController@patientIntoExcel')->name('PatientsController.patientIntoExcel');
  // Route::get('/export-patient-csv','PatientsController@patientIntoCsv')->name('PatientsController.patientIntoCsv');
  // Route::get('/export-mainData-csv','PatientsController@allDataIntoExcel')->name('PatientsController.allDataIntoExcel');
  Route::get('/export/patients','ExportsController@patients')->name('ExportsController.patients');
  Route::get('/export/medicalcases','ExportsController@cases')->name('ExportsController.cases');
  Route::get('/export/answers','ExportsController@answers')->name('ExportsController.answers');
  Route::get('/export/diagnosis_references','ExportsController@diagnosisReferences')->name('ExportsController.diagnosisReferences');
  Route::get('/export/custom_diagnoses','ExportsController@customDiagnoses')->name('ExportsController.customDiagnoses');
  Route::get('/export/drug_references','ExportsController@drugReferences')->name('ExportsController.drugReferences');
  Route::get('/export/additional_drugs','ExportsController@additionalDrugs')->name('ExportsController.additionalDrugs');
  Route::get('/export/management_references','ExportsController@managementReferences')->name('ExportsController.managementReferences');

  Route::get('/export/diagnoses','ExportsController@diagnoses')->name('ExportsController.diagnoses');
  Route::get('/export/drugs','ExportsController@drugs')->name('ExportsController.drugs');
  Route::get('/export/formulations','ExportsController@formulations')->name('ExportsController.formulations');
  Route::get('/export/managements','ExportsController@managements')->name('ExportsController.managements');
  Route::get('/export/nodes','ExportsController@nodes')->name('ExportsController.nodes');
  Route::get('/export/answer_types','ExportsController@answer_types')->name('ExportsController.answer_types');
  Route::get('/export/algorithms','ExportsController@algorithms')->name('ExportsController.algorithms');
  Route::get('/export/algorithm_versions','ExportsController@algorithmVersions')->name('ExportsController.algorithmVersions');
  Route::get('/export/cases_answers','ExportsController@casesAnswers2')->name('ExportsController.casesAnswers2');
  Route::get('/export/drug_analysis','ExportsController@drugAnalysis')->name('ExportsController.drugAnalysis');
  Route::get('/exports/diagnosis_list','ExportsController@diagnosesSummary')->name('ExportsController.diagnosesSummary');
  Route::get('/exports/drug_list','ExportsController@drugsSummary')->name('ExportsController.drugsSummary');

});


Route::get("/front-end-test",function(Request $request){
  return view("test.test");
});


Route::post("/post-data-test",function(Request $request){
  return $request->all();
});


Route::patch("/post-data-test",function(Request $request){
  return $request->all();
});





