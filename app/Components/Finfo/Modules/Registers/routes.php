<?php

$controller = "App\Components\Finfo\Modules\Registers\RegisterController";

Route::group(['prefix' => 'register'], function()  use ($controller)
{
    Route::get('/subscriptions', array('as' => 'finfo.register.subscriptions', 'uses' =>  $controller . "@index"));

    Route::get('/checkout/{scp_id?}', array('as' => 'finfo.registers.checkout', 'uses' =>  $controller . "@getCheckout"));

    Route::post('/checkout', array('as' => 'finfo.registers.docheckout', 'uses' =>  $controller . "@saveCheckout"));

    Route::post('/check/domain_name', array('as' => 'finfo.registers.checkregister', 'uses' =>  $controller . "@validateDomainNameByEmail"));

    Route::get('/{subscription_name}/{currency_type}', array('as' => 'finfo.register', 'uses' =>  $controller . "@getRegister"));

    Route::get('/success', array('as' => 'finfo.registers.success', 'uses' =>  $controller . "@getRegisterSuccess"));

    Route::get('/verify/{token}', array('as' => 'finfo.register.verify', 'uses' =>  $controller . "@getToken"));

    Route::get('/{subscription_id}', array('as' => 'finfo.register', 'uses' =>  $controller . "@getRegister"));
});
Route::get('/do-securepay', array('as' => 'finfo.register.dosecurepay', 'uses' =>  $controller . "@doSecurePay"));    
Route::post('/do-register', array('as' => 'finfo.registers.register', 'uses' =>  $controller . "@doRegister"));