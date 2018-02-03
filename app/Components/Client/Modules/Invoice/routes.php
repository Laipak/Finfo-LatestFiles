<?php

$controller = "App\Components\Client\Modules\Invoice\InvoiceController";

//test get view invoice
Route::get('/view-invoice', array('as' => 'view-invoice', 'uses' =>  $controller . "@getViewInvoice"));

Route::get('/dl-invoice', array('as' => 'dl-invoice', 'uses' =>  $controller . "@getDLInvoice"));

Route::group(['prefix' => config('client.backend_url')."/invoices", 'middleware' => 'auth'], function() use ($controller)
{
	Route::get('{status?}', array('as' => 'client.invoices.backend', 'uses' =>  $controller . "@getInvoice"));

	Route::get('/checkout/{id?}', array('as' => 'client.invoices.backend.checkout', 'uses' =>  $controller . "@getInvoiceCheckout"));

	Route::post('/do-checkout', array('as' => 'client.invoices.backend.do-checkout', 'uses' =>  $controller . "@getInvoiceDoCheckout"));

	Route::get('/detail/{id?}', array('as' => 'client.invoices.backend.detail', 'uses' =>  $controller . "@getInvoiceDetail"));

	Route::get('/download/{id?}', array('as' => 'client.invoices.backend.download', 'uses' =>  $controller . "@getInvoiceDownload"));

});