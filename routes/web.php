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
Route::get('abort-authentication', '\App\Http\Controllers\Auth\LoginController@abortAuthentication');

// for registration
Route::post('/2fa', function () {
    return redirect(URL()->previous());
})->name('2fa')->middleware('2fa');

Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration');
Route::get('/2fa', 'Auth\RegisterController@completeRegistration');

Route::post('/user/password/reset', 'HomeController@forgotPassword')->name('home.forgotPassword');
Route::get('/check_password_reset_token/{id}', 'HomeController@checkToken')->name('home.checkToken');
Route::post('/reset_user_password', 'HomeController@makePassword')->name('home.makePassword');

Route::group(['middleware' => ['auth', '2fa']], function () {
    # Roles and users
    Route::resource('roles', 'RolesController');
    Route::resource('users', 'UsersController');
    Route::get('/user/profile', 'UsersController@profile')->name('users.profile');
    Route::get('/user/password', 'UsersController@showChangePassword');
    Route::post('/user/password', 'UsersController@changePassword')->name('users.changePassword');
    Route::post('/user/reset/{id}', 'UsersController@resetPassword')->name('users.resetPassword');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('roles/removeRole/{id}', 'RolesController@removeRolePermissionShow');
    Route::post('role/removePerm/{id}', 'RolesController@removeRolePermission');

    //for patient
    Route::get('/patients/duplicates', 'PatientsController@findDuplicates')->name('patients.findDuplicates');
    Route::post('/patients/duplicates/search', 'PatientsController@searchDuplicates')->name('patients.searchDuplicates');
    Route::post('/patients/duplicates/delete', 'PatientsController@destroy')->name('patients.destroy');
    Route::get('/patients/compare/{id1}/{id2}', 'PatientsController@compare')->name('patients.compare');
    Route::get('/patients/merge/{id1}/{id2}', 'PatientsController@mergeShow')->name('patients.mergeShow');
    Route::post('/patients/merge', 'PatientsController@merge')->name('patients.merge');

    Route::resource('patients', 'PatientsController')->only([
        'index', 'show', 'destroy',
    ]);

    //for medical case
    Route::get('/medical-cases/duplicates', 'MedicalCasesController@findDuplicates')->name('medical-cases.findDuplicates');
    Route::get('/medical-cases/duplicate2', 'MedicalCasesController@findDuplicates2')->name('medical-cases.findDuplicates2');

    Route::resource('medical-cases', 'MedicalCasesController')->only([
        'index', 'show', 'destroy',
    ]);
    Route::get('/medical-cases/compare/{id1}/{id2}', 'MedicalCasesController@compare')->name('medical-cases.compare');
    Route::get('/medical-cases/{medicalCaseId}/question/{questionId}', 'MedicalCasesController@medicalCaseQuestion')->name('medical-cases.medicalCaseQuestion');
    Route::post('/medical-cases/{medicalCaseId}/question/{questionId}/update', 'MedicalCaseAnswersController@update')->name('medical-cases.update');
    Route::get('/medical-cases/changes/{id}', 'MedicalCasesController@showCaseChanges')->name('medical-cases.showCaseChanges');
    Route::post('/medical-cases/duplicates/search', 'MedicalCasesController@searchDuplicates')->name('medical-cases.searchDuplicates');
    Route::post('/medicalCases/duplicates/delete', 'MedicalCasesController@destroy')->name('medical-cases.destroy');
    Route::post('/medicalCases/remove_follow_up', 'MedicalCasesController@deduplicate_redcap')->name('medical-cases.deduplicate_redcap');

    //for followup
    Route::get('/followUp/delayed', 'MedicalCasesController@followUpDelayed');
    Route::get('/followUp', 'MedicalCasesController@showFacilities');
    Route::get('/followUp/show/{id}', 'MedicalCasesController@showFacility')->name('MedicalCasesController.showFacility');

    //for questions
    Route::resource('questions', 'QuestionsController')->only([
        'index', 'show',
    ]);

    //for facilities
    Route::resource('facilities', 'FacilitiesController')->only([
        'index',
    ]);

    //for logs
    Route::resource('logs', 'LogsController')->only([
        'index',
    ]);
    Route::post('/logs/{log_file_name}', 'LogsController@downloadLog')->name('log-downloader');
    Route::get('/logs/{log_file_name}', 'LogsController@show');

    //for failed json folder
    Route::resource('failed', 'FailedJsonController')->only([
        'index',
    ]);

    //for audit trail
    Route::resource('audits', 'AuditsController')->only([
        'index', 'show',
    ]);

    //for health facilities
    Route::group(['middleware' => ['permission:Manage_Health_Facilities']], function () {
        Route::resource('health-facilities', 'HealthFacilityController')->only([
            'index', 'store', 'update', 'destroy',
        ]);
        //Medical staff Management in the context of Health Facilities
        Route::get('health-facilities/{health_facility}/manage-medical-staff', "HealthFacilityController@manageMedicalStaff");
        Route::post('health-facilities/{health_facility}/assign-medical-staff/{medical_staff}', "HealthFacilityController@assignMedicalStaff");
        Route::post('health-facilities/{health_facility}/unassign-medical-staff/{medical_staff}', "HealthFacilityController@unassignMedicalStaff");
        //Device Management in the context of Health Facilities
        Route::get('health-facilities/{health_facility}/manage-devices', "HealthFacilityController@manageDevices");
        Route::post('health-facilities/{health_facility}/assign-device/{device}', "HealthFacilityController@assignDevice");
        Route::post('health-facilities/{health_facility}/unassign-device/{device}', "HealthFacilityController@unassignDevice");
        //Algorithms Management in the context of Health Facilities
        Route::get('health-facilities/{health_facility}/manage-algorithms', "HealthFacilityController@manageAlgorithms");
        Route::get('health-facilities/{health_facility}/accesses', "HealthFacilityController@accesses");
        Route::get('health-facilities/versions/{algorithm_id}', "HealthFacilityController@versions");
        Route::post('health-facilities/{health_facility}/assign-version/{algorithm_id}/{version_id}', "HealthFacilityController@assignVersion");
        // Sticker Management in the contet of Health Facilities
        Route::get('health-facilities/{health_facility}/manage-stickers', "HealthFacilityController@manageStickers");
        Route::get('generate-stickers', 'StickerController@downloadView');
        // Token management
        Route::get('devices/{devices}/manage-tokens', "DeviceController@manageTokens");
        Route::get('devices/{devices}/revoke-tokens', 'DeviceController@revokeTokens');
    });

    //for Devices
    Route::resource('devices', 'DeviceController')->only([
        'index', 'store', 'update', 'destroy',
    ]);

    // Medical Staff
    Route::resource('medical-staff', 'MedicalStaffController');

    //for downloading exports
    // Route::get('/export-medicalCase-excel','MedicalCasesController@medicalCaseIntoExcel')->name('medical-cases.medicalCaseIntoExcel');
    // Route::get('/export-medicalCase-csv','MedicalCasesController@medicalCaseIntoCsv')->name('medical-cases.medicalCaseIntoCsv');
    // Route::get('/export-patient-excel','PatientsController@patientIntoExcel')->name('patients.patientIntoExcel');
    // Route::get('/export-patient-csv','PatientsController@patientIntoCsv')->name('patients.patientIntoCsv');
    // Route::get('/export-mainData-csv','PatientsController@allDataIntoExcel')->name('patients.allDataIntoExcel');
    Route::get('/export/patients', 'ExportsController@patients')->name('exports.patients');
    Route::get('/export/medicalcases', 'ExportsController@cases')->name('exports.cases');
    Route::get('/export/answers', 'ExportsController@answers')->name('exports.answers');
    Route::get('/export/diagnosis_references', 'ExportsController@diagnosisReferences')->name('exports.diagnosisReferences');
    Route::get('/export/custom_diagnoses', 'ExportsController@customDiagnoses')->name('exports.customDiagnoses');
    Route::get('/export/drug_references', 'ExportsController@drugReferences')->name('exports.drugReferences');
    Route::get('/export/additional_drugs', 'ExportsController@additionalDrugs')->name('exports.additionalDrugs');
    Route::get('/export/management_references', 'ExportsController@managementReferences')->name('exports.managementReferences');
    Route::get('/export/diagnoses', 'ExportsController@diagnoses')->name('exports.diagnoses');
    Route::get('/export/drugs', 'ExportsController@drugs')->name('exports.drugs');
    Route::get('/export/formulations', 'ExportsController@formulations')->name('exports.formulations');
    Route::get('/export/managements', 'ExportsController@managements')->name('exports.managements');
    Route::get('/export/nodes', 'ExportsController@nodes')->name('exports.nodes');
    Route::get('/export/answer_types', 'ExportsController@answer_types')->name('exports.answer_types');
    Route::get('/export/algorithms', 'ExportsController@algorithms')->name('exports.algorithms');
    Route::get('/export/algorithm_versions', 'ExportsController@algorithmVersions')->name('exports.algorithmVersions');
    Route::get('/export/cases_answers', 'ExportsController@casesAnswers2')->name('exports.casesAnswers2');
    Route::get('/export/drug_analysis', 'ExportsController@drugAnalysis')->name('exports.drugAnalysis');
    Route::get('/exports/diagnosis_list', 'ExportsController@diagnosesSummary')->name('exports.diagnosesSummary');
    Route::get('/exports/drug_list', 'ExportsController@drugsSummary')->name('exports.drugsSummary');

    // Route::post('/exports/exportZipByDate', ['as' => 'exports.exportZipByDate', 'uses' => 'ExportsController@exportZipByDate']);
    // Route::post('/exports/exportFlatZip', ['as' => 'exports.exportFlatZip', 'uses' => 'ExportsController@exportFlatZip']);
    Route::get('/exports/exportZip', 'ExportsController@selectDate')->name('exports.selectDate');
    Route::get('/exports/download/{file}', 'ExportsController@DownloadExport')->name('exports.download');
});
