<?php

$controller = "App\Components\Client\Modules\Webpage\WebpageController";
$backend_controller = "App\Components\Client\Modules\Webpage\WebpageBackendController";
/* if (Session::has('set_package_id') && Session::get('set_package_id') != 1) { */
    

Route::group(['prefix' => config('client.backend_url')."/webpage", 'middleware' => 'auth'], function() use ($backend_controller)
{
    Route::any('pages', array('as' => 'client.webpage.backend.pages', 'uses' => $backend_controller."@pages")); 
	Route::any('postpages', array('as' => 'client.webpage.backend.postpages', 'uses' => $backend_controller."@page_index"));
    Route::get('/', array('as' => 'client.webpage.backend.list', 'uses' => $backend_controller."@listAllPages"));
    Route::get('create', array('as' => 'client.webpage.backend.create', 'uses' => $backend_controller."@createPage"));
    Route::get('edit/{id}', array('as' => 'client.webpage.backend.edit', 'uses' => $backend_controller."@editPage"));
    Route::post('edit/{id}', array('as' => 'client.webpage.backend.update', 'uses' => $backend_controller."@updatePage"));
    Route::get('save', array('as' => 'client.webpage.backend.save', 'uses' => $backend_controller."@createPage"));
    Route::post('store', array('as' => 'client.webpage.backend.store', 'uses' => $backend_controller."@savePage"));
    Route::get('/soft-delete/{id}', array('as' => 'client.webpage.backend.soft.delete', 'uses' => $backend_controller."@getSoftDelete"));
    Route::post('/soft-delete-multi', array('as' => 'client.webpage.backend.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));
    Route::post('/publish-multi', array('as' => 'client.webpage.backend.publish.multi', 'uses' => $backend_controller."@publishMulti"));
    Route::post('/unpublish-multi', array('as' => 'client.webpage.backend.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
    Route::get('/move-upload-image', array('as' => 'client.webpage.backend.moveuploadimage', 'uses' => $backend_controller."@saveUploadImages"));    
    Route::post('/move-upload-image', array('as' => 'client.webpage.backend.moveuploadimage', 'uses' => $backend_controller."@saveUploadImages"));
    Route::get('/upload-feature-image', array('as' => 'client.webpage.backend.uploadfeatureimage', 'uses' => $backend_controller."@UploadFeatureImages"));    
    Route::post('/upload-feature-image', array('as' => 'client.webpage.backend.uploadfeatureimage', 'uses' => $backend_controller."@UploadFeatureImages"));    
    
    
          /* session data backent start*/
        Route::post('/data/sessionsave', array('as' => 'client.webpage.backend.preview', 'uses' => $backend_controller."@sessiondtata"));
      /*  session data end*/
});

/*}*/




