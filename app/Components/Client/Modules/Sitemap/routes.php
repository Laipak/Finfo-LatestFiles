<?php

$controller = "App\Components\Client\Modules\Sitemap\SitemapController";

Route::get('/sitemap', array('as' => 'client.sitemap', 'uses' => $controller . "@index"));
