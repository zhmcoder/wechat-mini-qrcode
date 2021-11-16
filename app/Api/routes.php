<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'Api',
    'namespace' => 'App\\Api\\Controllers',
    'middleware' => 'verifySign'
], function (Router $router) {
    $router->post('user/login', 'UserController@userLogin')->name('user.login');

    $router->post('Home/index', 'HomeController@index')->name('home.index');

    $router->post('Class/list', 'ClassController@list')->name('class.list');
    $router->post('Brand/list', 'BrandController@list')->name('Brand.list');
    $router->post('Goods/list', 'GoodsController@list')->name('goods.list');
    $router->post('Goods/detail', 'GoodsController@detail')->name('goods.detail');
});
