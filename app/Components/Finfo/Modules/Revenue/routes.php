<?php

$backend_controller = "App\Components\Finfo\Modules\Revenue\RevenueController";


Route::group(['prefix' => config('finfo.backend_url')."/revenue"], function() use ($backend_controller)
{
    Route::get('/ajax-get-data', array('as' => 'finfo.admin.revenue.ajax-get-data', 'uses' => $backend_controller."@getData"));

    Route::get('/{view?}', array('as' => 'finfo.admin.revenue.list', 'uses' => $backend_controller."@index"));

    Route::get('/export-excel/{date?}/{view?}', array('as' => 'finfo.admin.revenue.export-excel', 'uses' => $backend_controller."@exportExcel"));

});
