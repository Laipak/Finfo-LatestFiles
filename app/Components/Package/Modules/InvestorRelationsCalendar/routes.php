<?php
$controller = "App\Components\Package\Modules\InvestorRelationsCalendar\InvestorRelationsCalendarController";
$backend_controller = "App\Components\Package\Modules\InvestorRelationsCalendar\IRCalendarBackendController";
//if (Session::has('set_package_id') && Session::get('set_package_id') != 1) {
// InvestorRelationsCalendar Frontend
Route::get('/investor-relations-events', array('as' => 'package.investor-relations-calendar', 'uses' => $controller."@getFrontend"));

Route::get('/event/get-data', array('as' => 'package.investor-relations-calenda.get-data', 'uses' => $controller."@getData"));

Route::post('/event/ajax-get-data-listing', array('as' => 'package.investor-relations-calenda.get-data-listing', 'uses' => $controller."@ajaxGetCalendaList"));

// InvestorRelationsCalendar Backend
Route::group(['prefix'=> config('client.backend_url').'/investor-relations-events', 'middleware' => 'auth'], function() use ($backend_controller){

	// InvestorRelationsCalendar list page 
	Route::get('/', array('as' => 'package.admin.investor-relations-calendar', 'uses' => $backend_controller."@getList"));

	Route::get('/form/{id?}', array('as' => 'package.admin.investor-relations-calendar.form', 'uses' => $backend_controller."@createIrCalendar"));
        
	Route::post('/form/{id?}', array('as' => 'package.admin.investor-relations-calendar.save', 'uses' => $backend_controller."@postIrCalendar"));

	Route::get('/soft-delete/{id}', array('as' => 'package.admin.investor-relations-calendar.soft.delete', 'uses' => $backend_controller."@getSoftDelete"));

	Route::post('/soft-delete-multi', array('as' => 'package.admin.investor-relations-calendar.soft.delete.multi', 'uses' => $backend_controller."@softDeleteMulti"));
    
    Route::post('/publish-multi', array('as' => 'package.admin.investor-relations-calendar.publish.multi', 'uses' => $backend_controller."@publishMulti"));

    Route::post('/unpublish-multi', array('as' => 'package.admin.investor-relations-calendar.unpublish.multi', 'uses' => $backend_controller."@unPublishMulti"));
});
//}