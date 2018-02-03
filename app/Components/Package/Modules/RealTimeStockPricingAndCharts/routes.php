<?php

$controller = "App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockController";

$backend_controller = "App\Components\Package\Modules\RealTimeStockPricingAndCharts\StockBackendController";
//if (Session::has('set_package_id') && Session::get('set_package_id') != 1) {
	//frontend
	Route::get('/stock-information', array('as' => 'package.stock-information', 'uses' =>  $controller . "@getStockInformation"));
	//backend
	Route::group(['prefix' => config('client.backend_url').'/stock', 'middleware' => 'auth'], function() use ($backend_controller)
	{
		Route::get('/', array('as' => 'package.admin.stock', 'uses' => $backend_controller."@getStockAndPrice"));

		Route::get('/form', array('as' => 'package.admin.stock.form', 'uses' => $backend_controller."@getEdit"));

		Route::post('/save', array('as' => 'package.admin.stock.save', 'uses' => $backend_controller."@getSave"));

		Route::post('/save-new-api', array('as' => 'package.admin.stock.save-new-api', 'uses' => $backend_controller."@saveNewAPI"));
		
		/* NEW */
		
			Route::post('/save-new-chartapi', array('as' => 'package.admin.stock.save-new-chartapi', 'uses' => $backend_controller."@saveNewChartAPI"));
			
			Route::post('/remove-chartapi', array('as' => 'package.admin.stock.remove-new-chartapi', 'uses' => $backend_controller."@removechartAPI"));
	        	 
		
	    /* NEW	*/

		Route::get('/get-price', array('as' => 'package.admin.get-date', 'uses' => $backend_controller."@APIConnectDataStockListAPIRequest"));
	        Route::post('/remove-api', array('as' => 'package.admin.stock.remove-new-api', 'uses' => $backend_controller."@removeAPI"));
	        	  Route::post('/stockid', array('as' => 'package.admin.stock.stockid', 'uses' => $backend_controller."@stockid"));
	 
        	        
	        
	});

	Route::group(['prefix' => config('client.backend_url').'/stock'], function() use ($backend_controller)
	{
		Route::post('/get-data', array('as' => 'package.admin.stock.get-data', 'uses' => $backend_controller."@getData"));

		Route::post('/get-price', array('as' => 'package.admin.stock.get-price', 'uses' => $backend_controller."@getPrice"));

		Route::get('/get-test', array('as' => 'package.admin.stock.get-test', 'uses' => $backend_controller."@getTest"));
	});
//}