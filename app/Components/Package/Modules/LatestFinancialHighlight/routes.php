<?php

$controller = "App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialHighlightController";

$backend_controller = "App\Components\Package\Modules\LatestFinancialHighlight\LatestFinancialHighlightBackendController";

Route::group(['prefix' => config('client.backend_url'), 'middleware' => 'auth'], function() use ($backend_controller)
{
	Route::get('/latest-financial-highlights', array('as' => 'package.admin.latest-financial-highlights', 'uses' => $backend_controller."@index"));
        Route::get('/latest-financial-highlights/archive', array('as' => 'package.admin.latest-financial-highlights.archive', 'uses' => $backend_controller."@listArchive"));
	Route::get('/latest-financial-highlights/form', array('as' => 'package.admin.latest-financial-highlights.form', 'uses' => $backend_controller."@getCreate"));
	Route::post('/latest-financial-highlights/form/save', array('as' => 'package.admin.latest-financial-highlights.save', 'uses' => $backend_controller."@postSave"));
	Route::get('/latest-financial-highlights/form/{id}', array('as' => 'package.admin.latest-financial-highlights.form.id', 'uses' => $backend_controller."@getEdit"));
        Route::get('/latest-financial-highlights/delete/{id}', array('as' => 'package.admin.latest-financial-highlights.deleted.id', 'uses' => $backend_controller."@deleted"));
	Route::post('/latest-financial-highlights/form/update/{id}', array('as' => 'package.admin.latest-financial-highlights.form.update', 'uses' => $backend_controller."@postUpdate"));
        Route::get('/latest-financial-highlights/soft-delete-multi', array('as' => 'package.admin.latest-financial-highlights.form.getSoftDeleteMulti', 'uses' => $backend_controller."@softDeleteMulti"));
        Route::post('/latest-financial-highlights/soft-delete-multi', array('as' => 'package.admin.latest-financial-highlights.form.softDeleteMulti', 'uses' => $backend_controller."@softDeleteMulti"));
        Route::post('/latest-financial-highlights/publish-multi', array('as' => 'package.admin.latest-financial-highlights.form.publishMulti', 'uses' => $backend_controller."@publishMulti"));
        Route::post('/latest-financial-highlights/unpublish-multi', array('as' => 'package.admin.latest-financial-highlights.form.unpublishMulti', 'uses' => $backend_controller."@unpublishMulti"));
});
