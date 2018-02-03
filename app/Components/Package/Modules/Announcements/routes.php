<?php
$controller = "App\Components\Package\Modules\Announcements\AnnouncementController";
$backend_controller = "App\Components\Package\Modules\Announcements\AnnouncementBackendController";

// Announcement Frontend
Route::get('/announcements', array('as' => 'package.announcements', 'uses' => $controller."@getFrontend"));



Route::get('/announcement/preview', array('as' => 'preview.temp', 'uses' => $controller."@previewannouncements"));



Route::get('/announcements/{page_title}', array('as' => 'package.announcements.page.detail', 'uses' => $controller."@getFrontendPageDatail"));
Route::post('/announcements/filter', array('as' => 'package.announcements.filter', 'uses' => $controller."@getFilterData"));

// Announcement Backend
Route::group(['prefix'=> config('client.backend_url'), 'middleware' => 'auth'], function() use ($backend_controller){
	Route::get('/announcement/', array('as' => 'package.admin.announcements', 'uses' => $backend_controller."@getListAnnouncements"));
	Route::get('/announcement/form/{id?}', array('as' => 'package.admin.announcements.form', 'uses' => $backend_controller."@form"));
	Route::post('/announcement/save', array('as' => 'package.admin.announcements.post', 'uses' => $backend_controller."@postAnnouncement"));
        Route::get('/announcement/edit/{id}', array('as' => 'package.admin.announcements.edit', 'uses' => $backend_controller."@editAnnouncement"));
        Route::post('/announcement/update/{id}', array('as' => 'package.admin.announcements.update', 'uses' => $backend_controller."@updateAnnoucement"));
        Route::get('/announcement/delete/{id}', array('as' => 'package.admin.announcements.delete', 'uses' => $backend_controller."@deleteAnnouncement"));
        Route::post('/announcement/soft-delete-multi', array('as' => 'package.admin.announcements.softDeleteMulti', 'uses' => $backend_controller."@softDeleteMulti"));
        Route::post('/announcement/unpublish-multi', array('as' => 'package.admin.announcements.unpublishMulti', 'uses' => $backend_controller."@unpublishMulti"));
        Route::post('/announcement/publish-multi', array('as' => 'package.admin.announcements.publishMulti', 'uses' => $backend_controller."@publishMulti"));
        
        
        
        
       /* session data backent start*/
        Route::post('/data/sessionsave', array('as' => 'package.admin.data.preview', 'uses' => $backend_controller."@sessiondtata"));
      /*  session data end*/
      
      
      
      
        
        Route::post('/announcement/check-existing-quarter', 
                array(
                    'as' => 'package.admin.announcements.checkExistingQuarter', 
                    'uses' => $backend_controller."@AjaxCheckExistingQuarterAnnouncement"
                )
        );
});
