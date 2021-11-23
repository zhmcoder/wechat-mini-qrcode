<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'Api',
    'namespace' => 'App\\Api\\Controllers',
    //'middleware' => 'verifySign'
], function (Router $router) {
    $router->post('user/login', 'UserController@userLogin')->name('user.login');

    $router->post('Home/index', 'HomeController@index')->name('home.index');

    $router->post('Class/list', 'ClassController@list')->name('class.list');
    $router->post('Brand/list', 'BrandController@list')->name('Brand.list');
    $router->post('Goods/list', 'GoodsController@list')->name('goods.list');
    $router->post('Goods/detail', 'GoodsController@detail')->name('goods.detail');

    $router->post('Goods/search', 'GoodsController@search')->name('Goods.search');

    $router->post('Cart/add', 'OrderCartController@add')->name('Cart.add');
    $router->post('Cart/list', 'OrderCartController@list')->name('Cart.list');
    $router->post('Cart/update', 'OrderCartController@update')->name('Cart.update');
    $router->post('Cart/delete', 'OrderCartController@delete')->name('Cart.delete');

    $router->post('District/list', 'DistrictController@list')->name('District.list');
    $router->post('Address/list', 'AddressController@list')->name('Address.list');
    $router->post('Address/add', 'AddressController@add')->name('Address.add');
    $router->post('Address/update', 'AddressController@update')->name('Address.update');
    $router->post('Address/delete', 'AddressController@delete')->name('Address.delete');

});
