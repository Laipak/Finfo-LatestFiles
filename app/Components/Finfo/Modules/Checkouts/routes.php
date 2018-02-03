<?php

$controller = "App\Components\Finfo\Modules\Checkouts\CheckoutController";

Route::group(['prefix' => 'checkout'], function()  use ($controller)
{
    Route::get('/{subscription_id}', array('as' => 'finfo.checkouts.checkout', 'uses' =>  $controller . "@getPayment"));

});

Route::post('process-checkout/{subscription_id}', array('as' => 'finfo.checkouts.checkout', 'uses' =>  $controller . "@getPayment"));