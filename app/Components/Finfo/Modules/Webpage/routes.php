<?php

$controller = "App\Components\Finfo\Modules\Webpage\WebpageController";
$backend_controller = "App\Components\Finfo\Modules\Webpage\WebpageBackendController";

Route::group(['prefix' => config('finfo.backend_url')."/webpage"], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'finfo.webpage.backend.list', 'uses' => $backend_controller."@listAllPages"));
    Route::get('create', array('as' => 'finfo.webpage.backend.create', 'uses' => $backend_controller."@createPage"));
    Route::get('edit/{id}', array('as' => 'finfo.webpage.backend.edit', 'uses' => $backend_controller."@editPage"));
    Route::post('edit/{id}', array('as' => 'finfo.webpage.backend.update', 'uses' => $backend_controller."@updatePage"));
    Route::get('save', array('as' => 'finfo.webpage.backend.save', 'uses' => $backend_controller."@createPage"));
    Route::Post('store', array('as' => 'finfo.webpage.backend.store', 'uses' => $backend_controller."@savePage"));
    Route::get('/soft-delete/{id}', array('as' => 'finfo.webpage.backend.soft.delete', 'uses' => $backend_controller."@getSoftDelete"));
    Route::post('/soft-delete-multi', array('as' => 'finfo.webpage.backend.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));
    Route::post('/publish-multi', array('as' => 'finfo.webpage.backend.publish.multi', 'uses' => $backend_controller."@publishMulti"));
    Route::post('/unpublish-multi', array('as' => 'finfo.webpage.backend.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
    Route::get('/move-upload-image', array('as' => 'finfo.webpage.backend.moveuploadimage', 'uses' => $backend_controller."@saveUploadImages"));    
    Route::POST('/move-upload-image', array('as' => 'finfo.webpage.backend.moveuploadimage', 'uses' => $backend_controller."@saveUploadImages"));    
});
