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

    // 产品
    $router->resource('category', 'CategoryController')->names('category');
    $router->resource('supplier', 'SuppliersController')->names('supplier');
    $router->resource('brand', 'BrandController')->names('brand');
    $router->resource('shop', 'ShopController')->names('shop');

    // 产品操作
    $router->resource('goods/class', 'GoodsClassController')->names('goods.class');
    $router->resource('goods/list', 'GoodsController')->names('goods.list');
    $router->post("goods/addGoodsAttr", "GoodsController@addGoodsAttr")->name("addGoodsAttr");
    $router->post("goods/addGoodsAttrValue", "GoodsController@addGoodsAttrValue")->name("addGoodsAttrValue");

});
