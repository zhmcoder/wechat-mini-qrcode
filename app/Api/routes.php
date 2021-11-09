<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'Api',
    'namespace' => 'App\\Api\\Controllers',
    //'middleware' => 'verifySign'
], function (Router $router) {
    $router->post('user/login', 'UserController@userLogin')->name('user.login');

    $router->get('Home/index', 'HomeController@index')->name('home.index');
});
