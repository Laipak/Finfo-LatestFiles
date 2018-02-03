<?php

$controller = "App\Components\Client\Modules\CompanyInfo\CompanyInfoController";


Route::get('/company-info', array('as' => 'client.company_info', 'uses' =>  $controller . "@getCompanyInfo"));
Route::get('/company-info/{slug?}', array('as' => 'client.company_info.slug', 'uses' =>  $controller . "@getSlugContents"));

Route::get('/preview/temp', array('as' => 'client.temp.preview', 'uses' => $controller."@previewannouncements"));