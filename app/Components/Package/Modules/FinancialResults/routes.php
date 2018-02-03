<?php

$controller = "App\Components\Package\Modules\FinancialResults\FinancialResultController";

//frontend
Route::get('/financial-result', array('as' => 'package.financial-result', 'uses' => $controller."@getFinancialResult"));
Route::get('/financial-result/{financial_archive_name}', array('as' => 'package.financial-result.financial_archive_name', 'uses' => $controller."@getArchivedFinancailResult"));
//Route::get('/financial-result/generatepdf/{financialId}', array('as' => 'package.financial-result.downloadPDF', 'uses' => $controller."@generateArchiveResultDownloadPDF"));



