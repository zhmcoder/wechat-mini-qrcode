<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Controllers\ContentController;
use Andruby\DeepAdmin\Models\EntityField;
use App\Models\Category;
use SmallRuralDog\Admin\Components\Attrs\SelectOption;
use SmallRuralDog\Admin\Components\Form\Checkbox;
use SmallRuralDog\Admin\Components\Form\InputNumber;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Grid\Image;
use SmallRuralDog\Admin\Components\Grid\Tag;
use SmallRuralDog\Admin\Components\Widgets\Alert;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;
use SmallRuralDog\Admin\Layout\Content;
use SmallRuralDog\Admin\Layout\Row;

class CategoryController extends ContentController
{
    public function grid()
    {
        $grid = new Grid(new Category());
        $grid->model()->with(['children']);
        $grid->model()->where('parent_id', 0);
        $grid->tree();
        $grid->column('id','序号')->width(50);
        $grid->column('name','分类名称');
        $grid->column('logo','图标')->component(Image::make()->preview()->style('height:40px;width:40px;'));

        $grid->column('order','排序');
        $fields = EntityField::query()->find(259);
        $option = $this->_selectOptionList($fields);
        $grid->column('status', '状态')->customValue(function ($row, $value) use ($option) {
            return $option['option'][$value] ?? '';
        })->component(function () {
            $type = [
                '禁用' => 'warning',
                '启用' => 'success',
            ];
            return Tag::make()->type($type);
        })->width(100);


        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Category());

        $form->item('parent_id', '上级菜单')->component(Select::make(0)->options(function () {
            return Category::query()->where('parent_id', 0)->orderBy('order')->get()->map(function ($item) {
                return SelectOption::make($item->id, $item->name);
            })->prepend(SelectOption::make(0, '一级菜单'));
        }));
        $form->item('name', '名称')->inputWidth(5)->required();
        $form->item('logo', '图标')->required()->component(Upload::make()->image()->uniqueName());
        $form->item('order', '排序')->component(InputNumber::make(1));

        $form->item('status', '状态')->component(Checkbox::make(1, "启用"));

        return $form;
    }
}

