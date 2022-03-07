<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Components\Widgets\Alert;
use Andruby\DeepAdmin\Controllers\AdminController;
use Andruby\DeepAdmin\Layout\Content;
use Andruby\DeepAdmin\Layout\Row;

class HomeController extends AdminController
{
    public function index(Content $content)
    {
        return $content;
    }

    public function home(Content $content)
    {
        $content->className('m-10')
            ->row(function (Row $row) {
                $row->gutter(10);
                $row->column(24, Alert::make("你好，同学！！", "欢迎使用 " . env('ADMIN_NAME', 'DeepAdmin'))->showIcon()->closable(false)->type("success"));
            });
        return $content;
    }
}

