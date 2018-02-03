<?php
$controller = "App\Components\Package\Modules\EmailAlerts\EmailAlertController";
$backend_controller = "App\Components\Package\Modules\EmailAlerts\EmailAlertBackendController";
//if (Session::has('set_package_id') && Session::get('set_package_id') == 3) {
    // Email Alert Frontend
    Route::get('/email-alerts', array('as' => 'package.email-alerts', 'uses' => $controller."@getFrontend"));

    Route::post('/email-alerts', array('as' => 'package.post.email-alerts', 'uses' => $controller."@postEmilAlerts"));

    Route::get('/email-alerts/unsubscribe/{email}/{id?}', array('as' => 'package.email-alerts.unsubscribe', 'uses' => $controller."@getUnsubscribe"));

    Route::post('/email-alerts/unsubscribe', array('as' => 'package.email-alerts.post-unsubscribe', 'uses' => $controller."@doUnsubscribe"));


    // Email Alert Backend
    Route::group(['prefix'=> config('client.backend_url').'/email-alerts', 'middleware' => 'auth'], function() use ($backend_controller){

            // Email Alert list page 
            Route::get('/', array('as' => 'package.admin.email-alerts', 'uses' => $backend_controller."@getList"));

            Route::get('/form/{id?}', array('as' => 'package.admin.email-alerts.form', 'uses' => $backend_controller."@createEamilAlert"));

            Route::post('/form/{id?}', array('as' => 'package.admin.email-alerts.post', 'uses' => $backend_controller."@postEamilAlert"));

        Route::get('/soft-delete/{id?}', array('as' => 'package.admin.email-alerts.soft-delete', 'uses' => $backend_controller."@getSoftDelete"));

        Route::post('/soft-delete-multi', array('as' => 'package.admin.email-alerts.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));

        Route::get('/export-cv', array('as' => 'package.admin.email-alerts.export-cv', 'uses' => $backend_controller."@exportExcel"));

        Route::get('/send-email', array('as' => 'package.admin.email-alerts.send-mail', 'uses' => $backend_controller."@sendMailToAllClient"));

        Route::post('/publish-multi', array('as' => 'package.admin.email-alerts.publish.multi', 'uses' => $backend_controller."@publishMulti"));

        Route::post('/unpublish-multi', array('as' => 'package.admin.email-alerts.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
    });
//}