<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'api',
    'namespace' => 'App\\Api\\Controllers',
    'middleware' => 'verifySign'
], function (Router $router) {
    $router->post('user/login', 'UserController@userLogin')->name('user.login');
});
