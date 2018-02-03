<?php

$controller = "App\Components\Client\Modules\Logins\LoginController";

Route::get('/admin/login', array('as' => 'client.login', 'uses' =>  $controller . "@index"));

Route::post('/do-login', array('as' => 'client.do.login', 'uses' =>  $controller . "@doLogin"));

Route::get('/logout', array('as' => 'client.logout', 'uses' =>  $controller . "@logout"));
