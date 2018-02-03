<?php

$controller = "App\Components\Package\Modules\Presentation\PresentationController";

$backend_controller = "App\Components\Package\Modules\Presentation\PresentationBackendController";
// Route::group(['prefix' => config('finfo.backend_url'), 'middleware' => 'auth'], function() use ($controller)
Route::group(['prefix' => config('client.backend_url').'/presentation', 'middleware' => 'auth'], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'package.admin.presentation', 'uses' => $backend_controller."@getList"));

    Route::get('/form/{id?}', array('as' => 'package.admin.presentation.form', 'uses' => $backend_controller."@getForm"));

    Route::post('/form/{id?}', array('as' => 'package.admin.presentation.save', 'uses' => $backend_controller."@postSave"));

    Route::post('/temp-upload-pdf', array('as' => 'package.admin.presentation.temp-upload-pdf', 'uses' => $backend_controller."@doTempUploadPdf"));

    Route::get('/soft-delete/{id?}', array('as' => 'package.admin.presentation.soft-delete', 'uses' => $backend_controller."@getSoftDelete"));

    Route::post('/soft-delete-multi', array('as' => 'package.admin.presentation.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));

    Route::post('/publish-multi', array('as' => 'package.admin.presentation.publish.multi', 'uses' => $backend_controller."@publishMulti"));

    Route::post('/unpublish-multi', array('as' => 'package.admin.presentation.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
    
    Route::post('/check-existing-quarter', array('as' => 'package.admin.presentation.check-existing-quarter', 'uses' => $backend_controller."@aJaxCheckExistingPresentation"));
});
