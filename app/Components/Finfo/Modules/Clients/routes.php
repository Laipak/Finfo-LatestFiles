<?php

$backend_controller = "App\Components\Finfo\Modules\Clients\ClientBackendController";

Route::group(['prefix' => config('finfo.backend_url')."/clients"], function() use ($backend_controller)
{
	Route::get('/', array('as' => 'finfo.admin.clients.list', 'uses' =>  $backend_controller . "@index"));

	Route::get('/{id}', array('as' => 'finfo.admin.clients.edit', 'uses' =>  $backend_controller . "@edit"));

	Route::post('/', array('as' => 'finfo.admin.clients.update', 'uses' =>  $backend_controller . "@postEdit"));

    Route::post('/approve', array('as' => 'finfo.admin.clients.approve', 'uses' => $backend_controller . "@approve"));

	Route::get('/detail/{client_id}', array('as' => 'finfo.admin.clients.detail', 'uses' =>  $backend_controller . "@getDetail"));
	
	Route::get('/invoices', array('as' => 'finfo.admin.clients.invoices', 'uses' =>  $backend_controller . "@getInvoice"));

	Route::get('/invoices/detail/{invoice_id}', array('as' => 'finfo.admin.clients.invoices.detail', 'uses' =>  $backend_controller . "@getInvoiceDetail"));

	Route::post('/reject', array('as' => 'finfo.admin.clients.reject', 'uses' => $backend_controller . "@reject"));

	Route::post('/check/domain', array('as' => 'finfo.admin.clients.domain', 'uses' => $backend_controller . "@validateDomainName"));

	Route::post('/check/domain_with_id', array('as' => 'finfo.admin.clients.domain.id', 'uses' => $backend_controller . "@checkDomainWithId"));

	Route::post('/check/client_payment', array('as' => 'finfo.admin.clients.check.payment', 'uses' => $backend_controller . "@checkClientPayment"));
	
	Route::post('/check/clone_details', array('as' => 'finfo.admin.clients.check.clone', 'uses' => $backend_controller . "@checkClonedetails"));
	
	Route::post('/clone', array('as' => 'finfo.admin.clients.clone', 'uses' => $backend_controller . "@cloning"));
		
});
