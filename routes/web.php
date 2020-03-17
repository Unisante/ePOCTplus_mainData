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
Route::get('/patients','PatientsController@index');
Route::get('/patient/{id}','PatientsController@show');
Route::get('/patients/compare/{checkedValues}','PatientsController@compare');
