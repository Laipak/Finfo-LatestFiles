<?php

$controller = "App\Components\Finfo\Modules\Billing\BillingController";


Route::group(['prefix' => config('finfo.backend_url').'/billing/invoice'], function() use ($controller)
{
    Route::get('/generate-invoice/{generateInvoiceKey}', array('as' => 'finfo.admin.billing.generate-invoice', 'uses' =>  $controller . "@generateClientInvoice"));
    
    Route::get('/list/{status?}', array('as' => 'finfo.admin.billing.invoice', 'uses' =>  $controller . "@getInvoice"));

    Route::get('/form/{id?}', array('as' => 'finfo.admin.billing.invoice.form', 'uses' =>  $controller . "@getFormInvoice"));

    Route::get('/delete/{id?}/{status?}', array('as' => 'finfo.admin.billing.invoice.delete', 'uses' =>  $controller . "@getDeleteInvoice"));

    Route::get('/detail/{id?}', array('as' => 'finfo.admin.billing.invoice.detail', 'uses' =>  $controller . "@getInvoiceDetail"));

    Route::get('/download/{id?}', array('as' => 'finfo.admin.billing.invoice.download', 'uses' =>  $controller . "@getInvoiceDownload"));

    Route::post('/delete-multi', array('as' => 'finfo.admin.billing.invoice.delete-multi', 'uses' =>  $controller . "@deleteMulti"));

    Route::post('/form/{id?}', array('as' => 'finfo.admin.billing.invoice.update', 'uses' =>  $controller . "@getInvoiceUpdate"));
});