<?php

$controller = "App\Components\Finfo\Modules\Pricing\PricingController";

Route::get('/pricing', array('as' => 'finfo.pricing', 'uses' => $controller . "@index"));
