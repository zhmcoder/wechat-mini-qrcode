<?php

use Illuminate\Routing\Router;

Route::group([
    'domain' => config('admin.route.domain'),
    'prefix' => config('admin.route.api_prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@home')->name('admin.home');
    $router->get('/home', 'HomeController@home')->name('admin.home');
});
