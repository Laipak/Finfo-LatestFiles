<?php

$controller = "App\Components\Client\Modules\Menus\MenuController";

Route::group(['prefix' => 'menu'], function()  use ($controller)
{
    Route::get('/', array('as' => 'client.menu.list', 'uses' =>  $controller . "@index"));
});
