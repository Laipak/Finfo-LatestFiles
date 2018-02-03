<?php

$backend_controller = "App\Components\Finfo\Modules\Packages\PackageBackendController";

Route::group(['prefix' => config('finfo.backend_url')], function() use ($backend_controller)
{
	Route::get('/packages', array('as' => 'finfo.admin.packages', 'uses' =>  $backend_controller . "@index"));

	Route::get('/packages/{package_id}', array('as' => 'finfo.admin.packages', 'uses' =>  $backend_controller . "@addModule"));
});