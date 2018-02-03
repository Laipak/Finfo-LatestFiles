<?php

$controller = "App\Components\Client\Modules\Social\SocialMediaController";



// Backend Setting
Route::group(['prefix' => config('client.backend_url')."/social",  'middleware' => 'auth'], function()  use ($controller)
{
  Route::get('/', array('as' => 'client.admin.social', 'uses' =>  $controller . "@index"));
  
  /*
  Route::post('/change', $controller . "@change");
  Route::post('/upload/logo', array('as' => 'client.admin.setting.upload.logo', 'uses' => $controller."@doCompanyUploadLogo"));
  Route::post('/upload/company-logo', array('as' => 'client.admin.setting.upload.logo', 'uses' => $controller."@doSettingUploadImage"));
  Route::post('/upload/favicon', array('as' => 'client.admin.setting.upload.favicon', 'uses' => $controller."@doCompanyUploadFavicon"));
  
  Route::post('/upload/banners', array('as' => 'client.admin.setting.upload.banners', 'uses' => $controller."@dobannersUpload"));
  
  Route::get('/load/banners', array('as' => 'client.admin.setting.load.banners', 'uses' => $controller."@dobannersLoad"));
  
  Route::post('/order/banners', array('as' => 'client.admin.setting.order.banners', 'uses' => $controller."@dobannersOrder"));
  
  Route::post('/banner/delete', array('as' => 'client.admin.setting.banner.delete', 'uses' => $controller."@dobannerDelete"));
  */
  
  
});