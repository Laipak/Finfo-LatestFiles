<?php
$controller = "App\Components\Package\Modules\MediaAccess\MediaAccessController";
$backend_controller = "App\Components\Package\Modules\MediaAccess\MediaAccessBackendController";

// Media Access Frontend
if (Session::has('set_package_id') && Session::get('set_package_id') == 3) {
Route::group(['prefix'=>'media-access'], function() use ($controller)
{   
        Route::get('/', array('as' => 'package.media-access', 'uses' => $controller."@getFrontend"));
        Route::get('/forgot-password', array('as' => 'package.media-access.forgot-password', 'uses' => $controller."@getForgotPassword"));
        Route::get('/reset-password', array('as' => 'package.media-access.reset-password', 'uses' => $controller."@getResetPasswordForm"));
        Route::post('/reset-password', array('as' => 'package.media-access.do-reset-password', 'uses' => $controller."@doResetPasswordForm"));
        Route::get('/thanks-you', array('as' => 'package.media-access.thanks-you', 'uses' => $controller."@getThanksYou"));
        Route::get('/logout', array('as' => 'package.media-access.logout', 'uses' => $controller."@mediaLogout"));
        Route::get('/lists', array('as' => 'package.media-access.lists', 'uses' => $controller."@listsMedia"));
        
        Route::post('/do-forgot-password', array('as' => 'package.media-access.do-forgot-password', 'uses' => $controller."@doForgotPassword"));
        Route::post('/register', array('as' => 'package.media-access.form', 'uses' => $controller."@doRegister"));
        Route::post('/login', array('as' => 'package.media-access.do-login', 'uses' => $controller."@doLogin"));
        Route::get('/login', array('as' => 'package.media-access.login', 'uses' => $controller."@getLogin"));
        Route::post('/download/{mediaFileId}', array('as' => 'package.media-access.download', 'uses' => $controller."@downloadMediaAccessFiles"));
});

// Media Access Backend
Route::group(['prefix'=> config('client.backend_url').'/media-access', 'middleware' => 'auth'], function() use ($backend_controller){
	Route::get('/', array('as' => 'package.admin.media-access', 'uses' => $backend_controller."@getMediaAccessList"));
        Route::get('/create', array('as' => 'package.admin.media-access.form', 'uses' => $backend_controller."@addMediaAccess"));
        Route::post('/create', array('as' => 'package.admin.media-access.do.createfile.form', 'uses' => $backend_controller."@doAddMediaAccessForm"));
        Route::get('/edit/{id}', array('as' => 'package.admin.media-access.edit', 'uses' => $backend_controller."@editMediaAccess"));
        Route::post('/edit/{id}', array('as' => 'package.admin.media-access.update', 'uses' => $backend_controller."@updateMediaAccess"));
        Route::get('/delete-file/{id}', array('as' => 'package.admin.media-access.delete-file', 'uses' => $backend_controller."@deleteFile"));
        Route::get('/multi-delete-file', array('as' => 'package.admin.media-access.get-multi-delete-files', 'uses' => $backend_controller."@mulitiDeleteFiles"));
        Route::post('/multi-delete-file', array('as' => 'package.admin.media-access.multi-delete-files', 'uses' => $backend_controller."@mulitiDeleteFiles"));
        Route::get('/multi-publish-files', array('as' => 'package.admin.media-access.get-multi-delete-files', 'uses' => $backend_controller."@mulitiPublishFiles"));
        Route::post('/multi-publish-files', array('as' => 'package.admin.media-access.multi-publish-files', 'uses' => $backend_controller."@mulitiPublishFiles"));
        Route::get('/multi-unpublish-files', array('as' => 'package.admin.media-access.get-multi-unpublish-files', 'uses' => $backend_controller."@mulitiUnpublishFiles"));
        Route::post('/multi-unpublish-files', array('as' => 'package.admin.media-access.multi-unpublish-files', 'uses' => $backend_controller."@mulitiUnpublishFiles"));
	Route::get('/approval/{id}', array('as' => 'package.admin.media-access.approval', 'uses' => $backend_controller."@getMediaUserApproval"));
        Route::get('/reject/{id}', array('as' => 'package.admin.media-access.reject', 'uses' => $backend_controller."@getMediaUserReject"));
        Route::get('/list-users', array('as' => 'package.admin.media-access.list-user', 'uses' => $backend_controller."@getListMediaAccessUsers"));
        Route::get('/delete/{id}', array('as' => 'package.admin.media-access.deleted', 'uses' => $backend_controller."@getMediaUserdelete"));
        Route::post('/mulit-delete', array('as' => 'package.admin.media-access.mulit-deleted', 'uses' => $backend_controller."@getMulitiMediaUserdelete"));
        Route::post('/multi-approval', array('as' => 'package.admin.media-access.multi-approval', 'uses' => $backend_controller."@getMediaUserMultiApproval"));
        Route::get('/export-report', array('as' => 'package.admin.media-access.export-report', 'uses' => $backend_controller."@getGenerateReportCSV"));
        Route::get('/list-category', array('as' => 'package.admin.media-access.list-category', 'uses' => $backend_controller."@listMediaAccessCategory"));
        Route::get('/create-category', array('as' => 'package.admin.media-access.create-category', 'uses' => $backend_controller."@createCategory"));
        Route::post('/create-category', array('as' => 'package.admin.media-access.store-category', 'uses' => $backend_controller."@storeCategory"));
        Route::get('/edit-category/{id}', array('as' => 'package.admin.media-access.edit-category', 'uses' => $backend_controller."@editCategory"));
        Route::post('/update-category', array('as' => 'package.admin.media-access.update-category', 'uses' => $backend_controller."@updateCategory"));
        Route::get('/delete-category/{id}', array('as' => 'package.admin.media-access.delete-category', 'uses' => $backend_controller."@deleteCategory"));
        Route::post('/multi-delete-category', array('as' => 'package.admin.media-access.multi-delete-category', 'uses' => $backend_controller."@mulitiDeleteCategory"));
        Route::get('/settings', array('as' => 'package.admin.media-access.settings', 'uses' => $backend_controller."@getSettingsMediaAccess"));
        Route::post('/do-settings', array('as' => 'package.admin.media-access.do.settings', 'uses' => $backend_controller."@doSettingsMediaAccess"));
});
}
