<?php

$controller = "App\Components\Finfo\Modules\Import\ImportController";


Route::group(['prefix' => config('finfo.backend_url')], function() use ($controller)
{
   
    Route::get('/import', array('middleware' => 'auth', 'as' => 'finfo.admin.import', 'uses' => $controller."@getImport"));
    
    Route::post('/importExcel', array('middleware' => 'auth', 'as' => 'finfo.admin.importExcel', 'uses' => $controller."@importExcel"));
  
   
   
});

