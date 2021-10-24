<?php

use Illuminate\Routing\Router;

Route::group([
    'domain' => config('admin.route.domain'),
    'prefix' => config('admin.route.api_prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('admin.index');
    $router->get('/home', 'HomeController@home')->name('admin.home');

    // 产品
    $router->resource('category', 'CategoryController')->names('category');
    $router->resource('supplier', 'SuppliersController')->names('supplier');
    $router->resource('brand', 'BrandController')->names('brand');
    $router->resource('shop', 'ShopController')->names('shop');

    // 产品操作
    $router->resource('goods/class', 'GoodsClassController')->names('goods.class');
    $router->resource('goods/list', 'GoodsController')->names('goods.list');
    $router->post("goods/addGoodsAttr", "GoodsController@addGoodsAttr")->name("goods.addGoodsAttr");
    $router->post("goods/addGoodsAttrValue", "GoodsController@addGoodsAttrValue")->name("goods.addGoodsAttrValue");

    // 首页配置
    $router->resource('home/config', 'HomeConfigController')->names('home.config');
    $router->resource('search/list', 'SearchController')->names('search.list');
    $router->resource('app/info', 'AppInfoController')->names('app.info');

    $router->get('home/column/relation_grid/{home_column_id}', 'HomeColumnController@relation_grid')->name('home.column.relation_grid');
    $router->get('home/column/relation', 'HomeColumnController@relation')->name('home.category.info');
    $router->resource('home/column', 'HomeColumnController')->names('home.column');

});
