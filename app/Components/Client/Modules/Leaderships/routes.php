<?php

$controller = "App\Components\Client\Modules\Leaderships\LeadershipController";


Route::get('/leadership', array('as' => 'client.leadership', 'uses' =>  $controller . "@getLeadership"));

