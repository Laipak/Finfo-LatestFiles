<?php

$controller = "App\Components\Client\Modules\Home\HomeController";


Route::get('/', array('as' => 'client.home', 'uses' =>  $controller . "@getHome"));


Route::post('/email', array('as' => 'client.home.email', 'uses' =>  $controller . "@Email"));



Route::post('/covert-time-zone', array('as' => 'client.home.covert-time-zone', 'uses' =>  $controller . "@convertTimewithTimezone"));

Route::get('/test-stock', array('as' => 'client.home.stock', 'uses' =>  $controller . "@getStock"));

Route::group(['prefix' => config('client.backend_url')], function() use ($controller)
{
   
});

