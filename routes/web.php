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
Route::resource('roles', 'RolesController');
Route::resource('user','UsersController');
Route::get('/home', 'HomeController@index')->name('home');
//for patient
Route::get('/patients','PatientsController@index');
Route::get('/patient/{id}','PatientsController@show');
Route::get('/patients/compare/{id1}/{id2}','PatientsController@compare');
//for medical case
Route::get('/medicalCases','medicalCasesController@index');
Route::get('/medicalCases/{id}','medicalCasesController@show');
Route::get('/medicalCases/compare/{id1}/{id2}','medicalCasesController@compare');
Route::get('/medicalCases/{medicalCaseId}/question/{questionId}','medicalCasesController@medicalCaseQuestion');
Route::post('/medicalCases/{medicalCaseId}/question/{questionId}/update','medicalCaseAnswersController@medicalCaseAnswerUpdate');
Route::get('/medicalCase/changes/{id}','medicalCasesController@showCaseChanges');

//for questions
Route::get('/questions','QuestionsController@index');
Route::get('/question/{id}','QuestionsController@show');
