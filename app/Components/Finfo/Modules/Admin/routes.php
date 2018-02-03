<?php

$controller = "App\Components\Finfo\Modules\Admin\AdminController";


Route::group(['prefix' => config('finfo.backend_url')], function() use ($controller)
{
    Route::get('/login', array('as' => 'finfo.admin.login', 'uses' =>  $controller . "@index"));
	
    Route::post('/do-login', array('as' => 'finfo.admin.do-login', 'uses' =>  $controller . "@doLogin"));

    Route::get('/logout', array('as' => 'finfo.admin.logout', 'uses' =>  $controller . "@logout"));

    Route::get('/forgot-password', array('as' => 'finfo.admin.forget.password', 'uses' => $controller."@getForgetPassword"));

    Route::post('/do-forgot-password', array('as' => 'finfo.admin.do.forget.password', 'uses' => $controller."@doForgetPassword"));

    Route::get('/reset-password/{token}', array('as' => 'finfo.admin.reset.password', 'uses' => $controller."@getResetPassword"));

    Route::post('/do-reset-password', array('as' => 'finfo.admin.do.reset.password', 'uses' => $controller."@doResetPassword"));

    Route::get('/dashboard', array('middleware' => 'auth', 'as' => 'finfo.admin.dashboard', 'uses' => $controller."@getDashboard"));

    Route::get('/setting', array('as' => 'finfo.admin.setting', 'uses' => $controller."@getSetting"));
    
    Route::post('/do-setting', array('as' => 'finfo.admin.do.setting', 'uses' => $controller."@doSetting"));

    Route::post('/edit-currency', array('as' => 'finfo.admin.edit-currency', 'uses' => $controller."@doEditCurrency"));

    Route::post('/do-currency', array('as' => 'finfo.admin.do.currency', 'uses' => $controller."@doDoCurrency"));
    
    Route::get('/do-delete/{id?}', array('as' => 'finfo.admin.do.delete', 'uses' => $controller."@doDodelete"));
    
    Route::get('/verified', array('as' => 'finfo.admin.verified', 'uses' =>  $controller . "@successVerify"));

    Route::get('/get-client-chart', array('as' => 'finfo.admin.client.chart', 'uses' =>  $controller . "@ajaxGetNewClient"));
    
    Route::post('/package-update-prices', array('as' => 'finfo.admin.do.update-prices', 'uses' =>  $controller . "@doUpdatePackagePrice"));
    
    Route::post('/get-package-prices', array('as' => 'finfo.admin.do.get-package-prices', 'uses' =>  $controller . "@getPackageDataById"));
    
      Route::get('/phone', array('as' => 'finfo.admin.do.phone', 'uses' => $controller."@phones"));
     Route::post('/phone', array('as' => 'finfo.admin.do.phone', 'uses' => $controller."@phone"));
});


