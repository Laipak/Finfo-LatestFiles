<?php

$controller = "App\Components\Finfo\Modules\Homes\HomeController";

Route::group(['prefix' => '/'], function()  use ($controller)
{
    $webpageBackendController = new App\Components\Finfo\Modules\Webpage\WebpageBackendController;

    Route::get('/', array('as' => 'finfo.home', 'uses' => $controller . "@index"));
    Route::get('/about-us', array('as' => 'finfo.about', 'uses' => $controller . "@about"));
    
    if ($webpageBackendController->checkCompanyWebpageIsExist() == true) {
        Route::get("/".Request::path(), array('as' => 'finfo.display.page', 'uses' => $controller."@displayPage"));
    } else {
        Route::get('/check-exclusive-subdomain/{subdomain}', array('as' => 'finfo.check.setting', 'uses' => $controller."@checkExclusiveDomainSetting"));
        Route::get('/get-admin-email', array('as' => 'finfo.get.email.setting', 'uses' => $controller."@getSettingEmailSendTo"));
    }
});

        
    


