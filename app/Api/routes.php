<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'api',
    'namespace'=> 'App\\Api\\Controllers',
//    'middleware' => 'verifySign'
], function (Router $router) {
    $router->get('home/lists', 'HomeController@index')->name('home.lists');
    $router->get('home/column_lists', 'HomeController@column_lists')->name('home.column_lists');
    $router->post('user/login', 'UserController@userLogin')->name('user.login');
});
