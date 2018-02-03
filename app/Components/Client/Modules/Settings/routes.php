<?php

$controller = "App\Components\Client\Modules\Settings\SettingController";

// Backend Setting
Route::group(['prefix' => config('client.backend_url')."/setting",  'middleware' => 'auth'], function()  use ($controller)
{
  Route::get('/', array('as' => 'client.admin.setting', 'uses' =>  $controller . "@index"));
  Route::post('/change', $controller . "@change");
  Route::post('/upload/logo', array('as' => 'client.admin.setting.upload.logo', 'uses' => $controller."@doCompanyUploadLogo"));
  Route::post('/upload/company-logo', array('as' => 'client.admin.setting.upload.logo', 'uses' => $controller."@doSettingUploadImage"));
  Route::post('/upload/favicon', array('as' => 'client.admin.setting.upload.favicon', 'uses' => $controller."@doCompanyUploadFavicon"));
  
  Route::post('/upload/banners', array('as' => 'client.admin.setting.upload.banners', 'uses' => $controller."@dobannersUpload"));
  
  Route::get('/load/banners', array('as' => 'client.admin.setting.load.banners', 'uses' => $controller."@dobannersLoad"));
  
  Route::post('/order/banners', array('as' => 'client.admin.setting.order.banners', 'uses' => $controller."@dobannersOrder"));
  
  Route::post('/banner/delete', array('as' => 'client.admin.setting.banner.delete', 'uses' => $controller."@dobannerDelete"));
  
  
  
  
    Route::get('/social', array('as' => 'client.admin.social', 'uses' => $controller."@getSocialFeed"));
    
   
        
});



/* New Social Root */ 

$scontroller = "App\Components\Client\Modules\Settings\SocialController";

Route::get('admin/setting/social/feedexcelexport', $scontroller."@feedexportexcel");

Route::get('admin/setting/social/feedpdfexport', $scontroller."@feedexportpdf");

Route::group(['prefix' => config('client.backend_url')."/setting",  'middleware' => 'auth'], function()  use ($scontroller)
{


Route::get('/social/view1', array('as' => 'client.admin.social.view1', 'uses' => $scontroller."@getSocialFeedNew1"));

 Route::post('/social/storeKeyFeed', array('as' => 'client.admin.setting.social.storeKeyFeed', 'uses' => $scontroller."@getSocialFeedKeyDetails"));
 
 Route::post('/social/getKeys', array('as' => 'client.admin.setting.social.getKeys', 'uses' => $scontroller."@getallKeys"));
 
 Route::post('/social/getsingleKey', array('as' => 'client.admin.setting.social.getsingleKey', 'uses' => $scontroller."@getsingleFeed"));
 
 Route::post('/social/getFeedbysort', array('as' => 'client.admin.setting.social.getFeedbysort', 'uses' => $scontroller."@getsingleFeed"));
 
 Route::post('/social/loadmoreKeyFeed', array('as' => 'client.admin.setting.social.loadmoreKeyFeed', 'uses' => $scontroller."@getsingleFeed"));
 
  Route::post('/social/deletesingleKeyFeed', array('as' => 'client.admin.setting.social.deletesingleKeyFeed', 'uses' => $scontroller."@deletesingleFeed"));
  
  
  Route::post('/social/reports', array('as' => 'client.admin.social.reports', 'uses' => $scontroller."@getSocialFeedReport"));
  
  Route::post('/social/mentionreports', array('as' => 'client.admin.social.mentionreports', 'uses' => $scontroller."@getSocialMentionReport"));
  
  Route::post('/social/sentimentreports', array('as' => 'client.admin.social.sentimentreports', 'uses' => $scontroller."@getSocialSentimentReport"));
  
   Route::post('/social/updateKeyFeed', array('as' => 'client.admin.setting.social.updateKeyFeed', 'uses' => $scontroller."@updateSocialFeedKeyDetails"));
   
   
   Route::post('/social/changesentiment', array('as' => 'client.admin.setting.social.changesentiment', 'uses' => $scontroller."@changeFeedSentiment"));
  
  
     Route::post('/social/getdefinition', array('as' => 'client.admin.setting.social.getdefinition', 'uses' => $scontroller."@getFeeddefinition")); 
  
  Route::get('/social/country-list', array('as' => 'client.admin.social.country-list', 'uses' => $scontroller."@getcountries"));
  
   
 //Route::get('/social/organization', array('as' => 'client.admin.social.organization', 'uses' => $scontroller."@generateOrganization"));
});



