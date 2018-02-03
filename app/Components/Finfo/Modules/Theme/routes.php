<?php
$backend_controller = "App\Components\Finfo\Modules\Theme\ThemeBackendController";

Route::group(['prefix' => config('finfo.backend_url')."/themes"], function() use ($backend_controller)
{
   Route::get('/', array('as' => 'finfo.admin.theme', 'uses' => $backend_controller."@index"));
   Route::post('/install-theme', array('as' => 'package.admin.theme.upload', 'uses' => $backend_controller."@installTheme"));
   Route::get('/uninstall-theme/{id}', array('as' => 'package.admin.theme.uninstall', 'uses' => $backend_controller."@uninstallTheme"));
   Route::get('/activate-theme/{id}', array('as' => 'package.admin.theme.activate', 'uses' => $backend_controller."@activateTheme"));
   Route::get('/deactivate-theme/{id}', array('as' => 'package.admin.theme.de-activate', 'uses' => $backend_controller."@deactivateTheme"));
});
