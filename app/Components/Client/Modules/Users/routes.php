<?php

$controller = "App\Components\Client\Modules\Users\UserController";
$backend_controller = "App\Components\Client\Modules\Users\UserBackendController";

Route::group(['prefix' => 'users'], function() use ($controller)
{
    Route::get('/forgot-password', array('as' => 'client.users.forget.password', 'uses' => $controller."@getForgetPassword"));

    Route::post('/do-forgot-password', array('as' => 'client.users.do.forget.password', 'uses' => $controller."@doForgetPassword"));

    Route::get('/reset-password/{token}', array('as' => 'client.users.reset.password', 'uses' => $controller."@getResetPassword"));

    Route::post('/do-reset-password', array('as' => 'client.users.do.reset.password', 'uses' => $controller."@doResetPassword"));

    Route::get('/success', array('as' => 'client.users.success', 'uses' => $controller."@getSuccess"));

    Route::get('/verify/{code}', array('as' => 'client.user.frontend.verify', 'uses' => $controller."@verify"));
    
});

//if (Session::has('package_id') && Session::get('package_id') != 1) {
    Route::group(['prefix' => config('client.backend_url')."/users", 'middleware' => 'auth'], function() use ($backend_controller)
    {
        Route::get('/', array('as' => 'client.user.backend.list', 'uses' => $backend_controller."@index"));

        Route::get('/form', array('as' => 'client.user.backend.create', 'uses' => $backend_controller."@getCreate"));

        Route::get('/form/{id?}', array('as' => 'client.user.backend.edit', 'uses' => $backend_controller."@getEdit"));

        Route::post('/save', array('as' => 'client.user.backend.save', 'uses' => $backend_controller."@postSave"));

        Route::post('/save/{id}', array('as' => 'client.user.backend.update', 'uses' => $backend_controller."@postUpdate"));

        Route::get('/soft-delete/{id}', array('as' => 'client.user.backend.soft.delete', 'uses' => $backend_controller."@getSoftDelete"));

        Route::post('/check-exit-email', array('as' => 'client.user.check-exit-email', 'uses' => $backend_controller."@postCheckExitEmail"));

        Route::post('/soft-delete-multi', array('as' => 'client.user.backend.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));

        Route::post('/publish-multi', array('as' => 'client.user.backend.publish.multi', 'uses' => $backend_controller."@publishMulti"));

        Route::post('/unpublish-multi', array('as' => 'client.user.backend.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));

    });
//}