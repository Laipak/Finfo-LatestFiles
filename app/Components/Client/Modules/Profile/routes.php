<?php

$controller = "App\Components\Client\Modules\Profile\ProfileController";


Route::group(['prefix' => config('client.backend_url')], function() use ($controller)
{


});

Route::group(['prefix' => config('client.backend_url'), 'middleware' => 'auth'], function() use ($controller)
{
    Route::get('/profile', array('as' => 'client.admin.profile', 'uses' => $controller."@getProfile"));

    Route::post('/profile/update', array('as' => 'client.admin.profile.update', 'uses' => $controller."@doProfileUpdate"));

    Route::post('/profile/update/password', array('as' => 'client.admin.profile.update.password', 'uses' => $controller."@doProfileUpdatePassword"));

    Route::post('/profile/check-exit-email', array('as' => 'client.profile.check-exit-email', 'uses' => $controller."@postCheckExitEmail"));

    Route::post('/profile/upload/picture', array('as' => 'client.admin.profile.upload.picture', 'uses' => $controller."@doProfileUploadPicture"));

});



