<?php

$controller = "App\Components\Package\Modules\Webcast\WebcastController";

$backend_controller = "App\Components\Package\Modules\Webcast\WebcastBackendController";
if (Session::has('set_package_id') && Session::get('set_package_id') != 1 ) {
Route::group(['prefix' => config('client.backend_url').'/webcast', 'middleware' => 'auth'], function() use ($backend_controller)
{
	Route::get('/', array('as' => 'package.admin.webcast', 'uses' => $backend_controller."@getlist"));

	Route::get('/form/{id?}', array('as' => 'package.admin.webcast.form', 'uses' => $backend_controller."@getForm"));

	Route::post('/form/{id?}', array('as' => 'package.admin.webcast.save', 'uses' => $backend_controller."@postSave"));

	Route::get('/delete/{id?}', array('as' => 'package.admin.webcast.delete', 'uses' => $backend_controller."@getDelete"));

	Route::post('/soft-delete-multi', array('as' => 'package.admin.webcast.delete.multi', 'uses' => $backend_controller."@deleteMulti"));

    Route::post('/publish-multi', array('as' => 'package.admin.webcast.publish.multi', 'uses' => $backend_controller."@publishMulti"));

    Route::post('/unpublish-multi', array('as' => 'package.admin.webcast.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
});
}
