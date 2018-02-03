<?php
//if (Session::has('set_package_id') && Session::get('set_package_id') != 1) {
    $controller = "App\Components\Package\Modules\PressReleases\PressReleaseController";

    Route::group(['prefix' => config('finfo.backend_url').'/press-releases', 'middleware' => 'auth'], function() use ($controller) {   
        Route::get('/', array('as' => 'package.admin.press-releases', 'uses' => $controller."@getList"));
        
        Route::get('/form/{id?}', array('as' => 'package.admin.press-releases.form', 'uses' => $controller."@getForm"));
        
      

        Route::post('/save/{id?}', array('as' => 'package.admin.press-releases.save', 'uses' => $controller."@postSave"));

        Route::post('/temp-upload-pdf', array('as' => 'package.admin.press-releases.temp-upload-pdf', 'uses' => $controller."@doTempUploadPdf"));

        Route::get('/delete/{id?}', array('as' => 'package.admin.press-releases.delete', 'uses' => $controller."@deletePressRelease"));

        Route::post('/change_enable', array('as' => 'package.admin.press-releases.change_enable', 'uses' => $controller."@changeEnablePressRelease"));

        Route::post('/soft-delete-multi', array('as' => 'package.admin.press-releases.delete.multi', 'uses' => $controller."@deleteMulti"));

        Route::post('/publish-multi', array('as' => 'package.admin.press-releases.publish.multi', 'uses' => $controller."@publishMulti"));

        Route::post('/unpublish-multi', array('as' => 'package.admin.press-releases.unpublish.multi', 'uses' => $controller."@unPublishMulti"));

        Route::post('/unpublish-multi', array('as' => 'package.admin.press-releases.unpublish.multi', 'uses' => $controller."@unPublishMulti"));

        Route::post('/check-existing-quarter', array('as' => 'package.admin.press-releases.check-existing-quarter', 'uses' => $controller."@aJaxcheckExistingPressRelease"));
        
        
        
          /* session data backent start*/
        Route::post('/data/sessionsave', array('as' => 'package.admin.data.preview', 'uses' => $controller."@sessiondtata"));
      /*  session data end*/
        
        
    });
    
      Route::get('/pressrelease/preview', array('as' => 'preview.temp', 'uses' => $controller."@previewpress"));

    //frontend
    Route::post('/press-releases/filter', array('as' => 'package.admin.press-releases.filter', 'uses' => $controller."@getFilterData"));
    Route::get('/press-releases', array('as' => 'package.press-releases', 'uses' => $controller."@getFrontend"));
    Route::get('/press-releases/{page_title}', array('as' => 'package.press-releases.page.detail', 'uses' => $controller."@getFrontendPageDatail"));
//}