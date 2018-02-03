<?php

$controller = "App\Components\Client\Modules\Company\CompanyController";

 Route::group(['prefix' => config('client.backend_url')."/company", 'middleware' => 'auth'], function() use ($controller)
//Route::group(['prefix' => config('client.backend_url')."/company"], function() use ($controller)
{
    Route::get('/', array('as' => 'client.admin.company', 'uses' => $controller."@getCompany"));

    Route::post('/upload/logo', array('as' => 'client.admin.company.upload.logo', 'uses' => $controller."@doCompanyUploadLogo"));

    Route::post('/upload/favicon', array('as' => 'client.admin.company.upload.favicon', 'uses' => $controller."@doCompanyUploadFavicon"));

    Route::post('/update', array('as' => 'client.admin.company.update', 'uses' => $controller."@postUpdate"));

});


