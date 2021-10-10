<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\DB;
use SmallRuralDog\Admin\Components\Form\Transfer;
use SmallRuralDog\Admin\Form;
use Andruby\DeepAdmin\Models\Entity;
use Andruby\DeepAdmin\Controllers\ContentController;
use SmallRuralDog\Admin\Grid;
use Andruby\DeepAdmin\Models\Content;

class CategoryController extends ContentController
{
    private $createUrl = "/home/category/create?";

    protected function create_url()
    {
        return $this->createUrl;
    }

    protected function grid_action(Grid\Actions $actions)
    {
        $actions->add(Grid\Actions\ActionButton::make('专栏管理')
            ->handler(Grid\Actions\ActionButton::HANDLER_ROUTE)
            ->uri('/home/category/info/{id}?timestamp=' . time()));
    }

    public function info($id = null)
    {

        $entityId = 53;
        $entity = Entity::find($entityId);

        $form = new Form(new Content($entity->table_name));
        $form->action('admin-api/home/category/save_column?id=' . $id);

        $where = ['shelf_setting' => 0, 'is_stop_sell' => 0, 'state' => 0];
        $all_column = DB::table('column_info')->where($where)->get(['id as key', 'title as label'])->toArray();

        $added_column_ids = DB::table('category_column_ids')->where('category_id', $id)
            ->join('column_info', function ($join) use ($where) {
                $join->on('category_column_ids.column_id', '=', 'column_info.id')->where($where);
            })->pluck('column_id');

        $category_info = DB::table('category_info')->find($id);

        $form->item('column_ids', '管理频道内容')
            ->component(Transfer::make($all_column)->data($all_column)
                ->targetOrder('push')
                ->componentValue($added_column_ids)
                ->titles(['专栏列表', '频道：' . $category_info->name])
                ->filterable(true));

        return $form;
    }

    public function save_column()
    {
        $category_id = request('id');
        $column_ids = request('column_ids');
        if (empty($category_id) || empty($column_ids)) {
            return \Admin::responseError('参数错误');
        }

        DB::table('category_column_ids')->where('category_id', $category_id)->delete();

        foreach ($column_ids as $column_id) {
            $item['category_id'] = $category_id;
            $item['column_id'] = $column_id;
            $item['updated_at'] = date('Y-m-d H:i:s', time());
            $item['created_at'] = date('Y-m-d H:i:s', time());
            DB::table('category_column_ids')->insert($item);
        }

        return \Admin::responseMessage("保存成功");


    }


    public function column_info()
    {

        $entitiesModel = config('admin.database.entities_model');

        $grid = new Grid(new $entitiesModel());

        $grid->defaultSort('id', 'desc');
        $grid->column('id', "ID")->width(80)->sortable();

        $grid->column('name', "模型名称");
        $grid->column('table_name', '数据库表名');
        $grid->column('description', "描述");
        $grid->column('created_at', '添加时间');
        $grid->column('updated_at', '更新时间');
        $grid->actions(function (Grid\Actions $actions) {
            $actions->add(Grid\Actions\ActionButton::make("数据")->order(3)
                ->handler('route')->uri('/entities/content?entity_id={id}'));
            $actions->add(Grid\Actions\ActionButton::make("字段管理")->order(3)
                ->handler('route')->uri('/entities/entity-field?entity_id={id}'));
        })->actionWidth(120);
//        $grid->column('is_show_content_manage', "状态")->width(100)->align("center")->component(Boole::make());

        return $grid;
    }
}

