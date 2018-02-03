<?php

$controller = "App\Components\Finfo\Modules\Term\TermController";
Route::get('/terms', array('as' => 'term-of-service', 'uses' => $controller . "@index"));
Route::get('/privacy', array('as' => 'privacy', 'uses' => $controller . "@privacy"));
Route::get('/agreement', array('as' => 'agreement', 'uses' => $controller . "@agreement"));

