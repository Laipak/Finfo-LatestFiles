<?php

$controller = "App\Components\Finfo\Modules\APISync\APISyncController";
$backend_controller = "App\Components\Finfo\Modules\APISync\APISyncBackendController";

Route::group(['prefix' => config('finfo.backend_url')."/apisync"], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'finfo.apisync.backend.api_connect', 'uses' => $backend_controller."@APIConnect"));
    
});
