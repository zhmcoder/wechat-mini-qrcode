<?php

use Illuminate\Routing\Router;

Route::group([
    'domain' => config('admin.route.domain'),
    'prefix' => config('admin.route.api_prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('admin.index');
    $router->get('/home', 'HomeController@index')->name('admin.home');
    $router->get('home/home', 'HomeController@home')->name('home.home');
    $router->get('home/chart_data', 'HomeController@chart_data')->name('admin.chart_data');

    $router->get('home/column/info/{id}', 'HomeColumnController@info')->name('home.column.info');
    $router->get('home/column/relation_grid/{home_column_id}', 'HomeColumnController@relation_grid')->name('home.column.relation_grid');
    $router->post('home/column/save_column', 'HomeColumnController@save_column')->name('home.column.save_column');
    $router->get('home/column/relation', 'HomeColumnController@relation')->name('home.category.info');
    $router->get('home/column/export', 'HomeColumnController@export')->name('home.column.export');
    $router->resource('home/column', 'HomeColumnController')->names('home.column');
    $router->resource('select/test', 'SelectTestController')->names('home.column');
    $router->get('select/related_select', 'SelectTestController@related_select')->name('select.test');
    $router->get('select/related_select_2', 'SelectTestController@related_select_2')->name('select.test');


    $router->get('home/category/info/{id}', 'CategoryController@info')->name('home.category.info');
    $router->post('home/category/save_column', 'CategoryController@save_column')->name('home.category.save_column');
    $router->resource('home/category', 'CategoryController')->names('home.category');

    $router->resource('app/list', 'AppController')->names('app.list');
    $router->resource('home/dialog', 'DialogController')->names('home.dialog');

    $router->get('select/sale_unit_grid', 'SelectTestController@sale_unit_grid')->name('select.sale_unit_grid');
    $router->post('select/sale_unit_save', 'SelectTestController@sale_unit_save')->name('select.sale_unit_save');


    $router->resource('tab/list', 'TabController')->names('tab.list');
    $router->get('tab/getTab', 'TabController@getTab')->name('tab.getTab');
    $router->get('tab/getFilter', 'TabController@getFilter')->name('tab.getFilter');
});
