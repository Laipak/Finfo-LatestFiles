<?php

$controller = "App\Components\Client\Modules\Admin\AdminController";

Route::group(['prefix' => config('client.backend_url'), 'middleware' => 'auth'], function() use ($controller)
{
    Route::get('/dashboard', array('as' => 'client.admin.dashboard', 'uses' => $controller."@getDashboard"));
    
   
});
