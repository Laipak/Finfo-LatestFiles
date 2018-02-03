<?php

$controller = "App\Components\Client\Modules\Package\PackageController";


Route::group(['prefix' => config('client.backend_url').'/package', 'middleware' => 'auth'], function() use ($controller)
{
    Route::get('/', array('as' => 'client.admin.package', 'uses' => $controller."@getPackage"));
    Route::get('/cancel-package', array('as' => 'client.admin.cancel.package', 'uses' => $controller."@cancelPackage"));
    Route::get('/upgrade/{id}', array('as' => 'client.admin.upgrade.package', 'uses' => $controller."@upgradePackage"));
    Route::post('/checkout', array('as' => 'client.upgrade.docheckout', 'uses' =>  $controller . "@saveCheckout"));
    Route::get('/history', array('as' => 'client.admin.package.history', 'uses' => $controller."@getHistory"));

});


