<?php

$controller = "App\Components\Finfo\Modules\Users\UserController";
$backend_controller = "App\Components\Finfo\Modules\Users\UserBackendController";

Route::group(['prefix' => 'user'], function() use ($controller)
{
    Route::get('/verify/{code}', array('as' => 'finfo.user.frontend.verify', 'uses' => $controller."@verify"));
});


Route::group(['prefix' => config('finfo.backend_url')."/users"], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'finfo.user.backend.list', 'uses' => $backend_controller."@index"));

    Route::get('/form', array('as' => 'finfo.user.backend.create', 'uses' => $backend_controller."@getCreate"));

    Route::get('/form/{id?}', array('as' => 'finfo.user.backend.edit', 'uses' => $backend_controller."@getEdit"));

    Route::post('/save', array('as' => 'finfo.user.backend.save', 'uses' => $backend_controller."@postSave"));

    Route::post('/save/{id}', array('as' => 'finfo.user.backend.update', 'uses' => $backend_controller."@postUpdate"));

    Route::get('/soft-delete/{id}', array('as' => 'finfo.user.backend.soft.delete', 'uses' => $backend_controller."@getSoftDelete"));

    Route::post('/soft-delete-multi', array('as' => 'finfo.user.backend.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));

    Route::post('/publish-multi', array('as' => 'finfo.user.backend.publish.multi', 'uses' => $backend_controller."@publishMulti"));
    Route::post('/unpublish-multi', array('as' => 'finfo.user.backend.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));

    Route::get('/profile', array('as' => 'finfo.user.profile', 'uses' => $backend_controller."@getProfile"));

    Route::post('/profile/update', array('as' => 'finfo.user.profile.update', 'uses' => $backend_controller."@postProfile"));

    Route::post('/check-exit-email', array('as' => 'finfo.user.check-exit-email', 'uses' => $backend_controller."@postCheckExitEmail"));

    Route::post('/profile/update/password', array('as' => 'finfo.user.profile.update.password', 'uses' => $backend_controller."@doProfileUpdatePassword"));


});
