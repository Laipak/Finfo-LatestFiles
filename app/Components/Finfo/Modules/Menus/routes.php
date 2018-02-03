<?php

//$controller = "App\Components\Finfo\Modules\Menus\MenuController";
$backend_controller = "App\Components\Finfo\Modules\Menus\MenuBackendController";

Route::group(['prefix' => config('finfo.backend_url')."/menus"], function() use ($backend_controller)
{
    Route::get('/', array('as' => 'finfo.menus.backend.list', 'uses' => $backend_controller."@index"));

    Route::get('/form/{id?}', array('as' => 'finfo.menus.backend.create', 'uses' => $backend_controller."@create"));

    Route::post('/store/{id?}', array('as' => 'finfo.menus.backend.store', 'uses' => $backend_controller."@store"));

    Route::get('/delete/{id?}', array('as' => 'finfo.menus.backend.menu.delete', 'uses' => $backend_controller."@deleteMenu"));

    Route::post('/soft-delete-multi', array('as' => 'finfo.menus.backend.menu.delete-multi', 'uses' => $backend_controller."@deleteMenuMulti"));

    Route::post('/unpublish-multi', array('as' => 'finfo.menus.backend.menu.unpublic-multi', 'uses' => $backend_controller."@unpublicMenuMulti"));

    Route::post('/publish-multi', array('as' => 'finfo.menus.backend.menu.public-multi', 'uses' => $backend_controller."@publicMenuMulti"));
    
});
