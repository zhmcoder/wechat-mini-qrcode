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


    $router->resource('brands', 'BrandsController')->names('brands');
    $router->resource('category', 'CategoryController')->names('category');

    //产品
    $router->resource('supplier', 'SuppliersController');
    $router->resource('brand', 'BrandController');
    $router->resource('shop', 'ShopController');
    $router->resource('goods/class', 'GoodsClassController');
    $router->resource('goods/list', 'GoodsController');

    //产品操作
    $router->post("goods/addGoodsAttr", "GoodsController@addGoodsAttr")->name("addGoodsAttr");
    $router->post("goods/addGoodsAttrValue", "GoodsController@addGoodsAttrValue")->name("addGoodsAttrValue");


    // 首页配置
    $router->resource('home/config', 'HomeConfigController')->names('home.config');

    $router->resource('search/list', 'SearchController')->names('search.list');

    $router->get('home/column/info/{id}', 'HomeColumnController@info')->name('home.column.info');
    $router->get('home/column/relation_grid/{home_column_id}', 'HomeColumnController@relation_grid')->name('home.column.relation_grid');
    $router->post('home/column/save_column', 'HomeColumnController@save_column')->name('home.column.save_column');
    $router->get('home/column/relation', 'HomeColumnController@relation')->name('home.category.info');
    $router->resource('home/column', 'HomeColumnController')->names('home.column');

});
