<?php

$controller = "App\Components\Client\Modules\Google\GoogleController";

Route::group(['prefix' => config('client.backend_url').'/google', 'middleware' => 'auth'], function() use ($controller){
	Route::get('/', array('as' => 'client.admin.google.report.view', 'uses' => $controller."@showGoogleReportView"));
	Route::post('/form', array('as' => 'google.clientid.add', 'uses' => $controller."@saveClientId"));
});

