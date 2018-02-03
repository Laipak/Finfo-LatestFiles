<?php

$controller = "App\Components\Package\Modules\FinancialAnnualReports\FinancialAnnualReportController";

$backend_controller = "App\Components\Package\Modules\FinancialAnnualReports\FinancialAnnualReportBackendController";


// Route::group(['prefix' => config('finfo.backend_url'), 'middleware' => 'auth'], function() use ($controller)
Route::group(['prefix' => config('finfo.backend_url').'/financial-annual-reports', 'middleware' => 'auth'], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'package.admin.financial-annual-reports', 'uses' => $backend_controller."@getList"));

    Route::get('/form/{id?}', array('as' => 'package.admin.financial-annual-reports.form', 'uses' => $backend_controller."@getform"));

    Route::post('/save/{id?}', array('as' => 'package.admin.financial-annual-reports.save', 'uses' => $backend_controller."@postSave"));

    Route::post('/temp-upload-pdf', array('as' => 'package.admin.financial-annual-reports.temp-upload-pdf', 'uses' => $backend_controller."@doTempUploadPdf"));

    Route::post('/temp-cover-image', array('as' => 'package.admin.financial-annual-reports.temp-cover-image', 'uses' => $backend_controller."@doTempUploadCover"));

    Route::get('/delete/{id?}', array('as' => 'package.admin.financial-annual-reports.delete', 'uses' => $backend_controller."@getDelete"));

    Route::post('/soft-delete-multi', array('as' => 'package.admin.financial-annual-reports.delete.multi', 'uses' => $backend_controller."@deleteMulti"));

    Route::post('/publish-multi', array('as' => 'package.admin.financial-annual-reports.publish.multi', 'uses' => $backend_controller."@publishMulti"));

    Route::post('/unpublish-multi', array('as' => 'package.admin.financial-annual-reports.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
});


//frontend
Route::get('/annual-report', array('as' => 'package.annual-report', 'uses' => $controller."@getAnnualReport")); 