<?php

$controller = "App\Components\Package\Modules\NewsletterAndBroadcast\NewsletterAndBroadcastController";

$backend_controller = "App\Components\Package\Modules\NewsletterAndBroadcast\NewsletterAndBroadcastBackendController";

Route::get('admin/newsletter-broadcast/send-schedulel', array('as' => 'package.admin.newsletter-broadcast.send-schedulel', 'uses' => $backend_controller."@schedulel"));


//if (Session::has('set_package_id') && Session::get('set_package_id') == 3) {
Route::group(['prefix' => config('client.backend_url').'/newsletter-broadcast', 'middleware' => 'auth'], function() use ($backend_controller)
{
	Route::get('/', array('as' => 'package.admin.newsletter-broadcast', 'uses' => $backend_controller."@getlist"));

    Route::get('/subscribes', array('as' => 'package.admin.newsletter-broadcast
        .subscribes', 'uses' => $backend_controller."@getsubscribes"));
  
  
	Route::get('/form/{id?}', array('as' => 'package.admin.newsletter-broadcast.form', 'uses' => $backend_controller."@getForm"));

	Route::post('/form/{id?}', array('as' => 'package.admin.newsletter-broadcast.save', 'uses' => $backend_controller."@postSave"));

   
	Route::post('/config', array('as' => 'package.admin.newsletter-broadcast.config', 'uses' => $backend_controller."@postconfig"));

	Route::post('/temp-upload-pdf', array('as' => 'package.admin.newsletter-broadcast.temp-upload-pdf', 'uses' => $backend_controller."@doTempUploadPdf"));

	Route::get('/view-template/{id?}', array('as' => 'package.admin.newsletter-broadcast.view.template', 'uses' => $backend_controller."@getViewTemplate"));

    Route::get('/soft-delete/{id}', array('as' => 'package.admin.newsletter-broadcast.soft.delete', 'uses' => $backend_controller."@getSoftDelete"));

    Route::post('/soft-delete-multi', array('as' => 'package.admin.newsletter-broadcast.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));

    Route::get('/news-detail/{id}', array('as' => 'package.admin.newsletter-broadcast.detail', 'uses' => $backend_controller."@getnewsDetail"));

    Route::get('/send-test/{id}', array('as' => 'package.admin.newsletter-broadcast.send-test', 'uses' => $backend_controller."@getSendMail"));

    Route::post('/upload-img', array('as' => 'package.admin.newsletter-broadcast.upload-img', 'uses' => $backend_controller."@uploadImage"));
 
});


//email seed list
Route::group(['prefix' => config('client.backend_url').'/newsletter-broadcast/email-seed-list', 'middleware' => 'auth'], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'package.admin.newsletter-broadcast.email-seed-list', 'uses' => $backend_controller."@getEmailSeedList"));
 	
 	 Route::get('/subscribes', array('as' => 'package.admin.newsletter-broadcast
        .subscribes', 'uses' => $backend_controller."@getsubscribes"));
 	
 	
    Route::get('/form/{id?}', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.form', 'uses' => $backend_controller."@getEmailSeedListForm"));

    Route::post('/form/{id?}', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.save', 'uses' => $backend_controller."@postEmailSeedList"));

    Route::get('/soft-delete/{id}', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.soft.delete', 'uses' => $backend_controller."@getEmailSeedSoftDelete"));

    Route::post('/soft-delete-multi', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.soft.delete.multi', 'uses' => $backend_controller."@EmailSeedsoftDeleteMulti"));

    Route::post('/publish-multi', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.publish.multi', 'uses' => $backend_controller."@EmailSeedpublishMulti"));

    Route::post('/unpublish-multi', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.unpublish.multi', 'uses' => $backend_controller."@EmailSeedunPublishMulti"));
    
      Route::get('/mailslisting', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.mailslisting', 'uses' => $backend_controller."@listingmails"));
    
    
     Route::post('/addmails', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.addmails', 'uses' => $backend_controller."@Multipleemails"));

      Route::post('/deletemail', array('as' => 'package.admin.newsletter-broadcast.email-seed-list.deletemail', 'uses' => $backend_controller."@Deletemail"));

});
//}