<?php

$controller = "App\Components\Finfo\Modules\Contact\ContactController";

Route::get('/contact', array('as' => 'finfo.contact', 'uses' => $controller . "@index"));
Route::post('/contact', array('as' => 'finfo.contact.save', 'uses' => $controller . "@save"));
