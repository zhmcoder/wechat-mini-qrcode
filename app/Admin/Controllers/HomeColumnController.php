<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Components\Grid\SortEdit;
use App\Models\ColumnInfo;
use App\Models\HomeColumn;
use App\Models\HomeColumnIds;
use Illuminate\Support\Facades\DB;
use SmallRuralDog\Admin\Components\Form\Transfer;
use SmallRuralDog\Admin\Components\Grid\Tag;
use SmallRuralDog\Admin\Components\Widgets\Card;
use SmallRuralDog\Admin\Form;
use Andruby\DeepAdmin\Models\Entity;
use Andruby\DeepAdmin\Controllers\ContentController;
use SmallRuralDog\Admin\Grid;
use Andruby\DeepAdmin\Models\Content;
use SmallRuralDog\Admin\Layout\Row;
use Illuminate\Database\Query\JoinClause;

// 货架关联专辑
class HomeColumnController extends ContentController
{
    protected function grid_action(Grid\Actions $actions)
    {
        $actions->add(Grid\Actions\ActionButton::make('专辑/套餐管理')
            ->handler(Grid\Actions\ActionButton::HANDLER_ROUTE)
            ->uri('/home/column/relation_grid/{id}?timestamp=' . time()));
    }

    public function info($id = null)
    {

        $entityId = 54;
        $entity = Entity::find($entityId);

        $form = new Form(new Content($entity->table_name));
        $form->action('admin-api/home/column/save_column?id=' . $id);

        $where = ['shelf_setting' => 0, 'is_stop_sell' => 0, 'state' => 0];
        $all_column = DB::table('column_info')->where('is_stop_sell', 0)->where('shelf_setting', 0)->where('state', 0)
            ->get(['id as key', 'title as label'])->toArray();

        $added_column_ids = DB::table('home_column_ids')->where('home_column_id', $id)
            ->join('column_info', function ($join) use ($where) {
                $join->on('home_column_ids.column_id', '=', 'column_info.id')->where($where);
            })->pluck('column_id');

        $home_column_info = DB::table('home_column')->find($id);

        $form->item('series_column_ids', '管理货架内容')
            ->component(Transfer::make($all_column)->data($all_column)
                ->targetOrder('push')
                ->componentValue($added_column_ids)
                ->titles(['专辑列表', '货架：' . $home_column_info->name])
                ->filterable(true));

        return $form;
    }

    public function save_column()
    {
        $home_column_id = request('id');
        $column_ids = request('column_ids');
        if (empty($home_column_id) || empty($column_ids)) {
            return \Admin::responseError('参数错误');
        }

        DB::table('home_column_ids')->where('home_column_id', $home_column_id)->delete();

        foreach ($column_ids as $column_id) {
            $item['home_column_id'] = $home_column_id;
            $item['column_id'] = $column_id;
            $item['updated_at'] = date('Y-m-d H:i:s', time());
            $item['created_at'] = date('Y-m-d H:i:s', time());
            HomeColumnIds::insert($item);
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

    public function relation_grid(\SmallRuralDog\Admin\Layout\Content $content, $home_column_id = null)
    {
        $grid_type = request('grid_type');

        $grid_left = $this->column_grid(1, 'home_column_ids', $home_column_id);
        $grid_right = $this->column_grid(2, 'column_info', $home_column_id);

        $homeColumnInfo = HomeColumn::query()->find($home_column_id);

        $content->row(function (Row $row) use ($grid_left, $grid_right, $homeColumnInfo) {
            $row->gutter(0)->className('mt-10');
            $row->column(12, Card::make()->header('货架：' . $homeColumnInfo['name'] . '，已关联内容')->bodyStyle(['padding' => '0', 'margin_left' => '100'])->content(
                $grid_left
            ));
            $row->column(12, Card::make()->header('专辑列表')->bodyStyle(['padding' => '0'])->content(
                $grid_right
            ));
        });
        if ($grid_type == 1) {
            return $this->isGetData() ? $grid_left : $content;
        } else {
            return $this->isGetData() ? $grid_right : $content;
        }
    }

    private function column_grid($grid_type, $table_name, $home_column_id)
    {
        $grid = new Grid(new Content($table_name));

        $grid->autoHeight();
        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->hideCreateButton();
        });

        $grid->quickSearch(['title'])->quickSearchPlaceholder("专辑名称");

        if ($grid_type == 1) {
            $grid->model()->where('home_column_id', $home_column_id)
                ->join('column_info', function (JoinClause $join) use ($table_name) {
                    $join->on($table_name . '.column_id', '=', 'column_info.id')
                        ->where('is_stop_sell', 0)->where('shelf_setting', 0)->where('state', 0);
                })->select(['home_column_ids.id as id', 'home_column_ids.sort as sort', 'column_info.title as title', 'column_info.column_type as column_type']);

            $grid->defaultSort('sort', 'asc');
            $grid->column('id', "ID")->width(80)->sortable();

        } else if ($grid_type == ColumnInfo::TYPE_PACKAGE) {
            $grid->model()->where('is_stop_sell', 0)->where('shelf_setting', 0)->where('state', 0)
                ->leftJoin('home_column_ids', function ($join) use ($home_column_id) {
                    $join->on('column_info.id', '=', 'home_column_ids.column_id')->where('home_column_ids.home_column_id', $home_column_id);
                })->select(['column_info.id as id', 'column_info.title as title', 'column_info.column_type as column_type', 'home_column_ids.home_column_id as home_column_id'])
                ->where('column_type', '<', ColumnInfo::TYPE_PICK);

            $grid->defaultSort('id', 'desc');
            $grid->column('id', "ID")->width(80)->sortable();
        }

        $grid->column('title', "专辑名称")->sortable();
        $grid->column('column_type', '类型')->sortable()->customValue(function ($row, $value) {
            return ColumnInfo::TYPE_LIST[$value];
        })->component(function () {
            $type = ['专辑' => 'info', '套餐' => 'success'];
            return Tag::make()->type($type);
        })->width(80);

        if ($grid_type == 1) {
            $grid->column('sort', '排序')->component(
                SortEdit::make()->action(config('admin.route.api_prefix') . '/entities/content/grid_sort_change?entity_id=55')
            )->width(80)->sortable();
        }

        $grid->actions(function (Grid\Actions $actions) use ($grid_type, $home_column_id) {
            $actions->hideDeleteAction();
            $actions->hideEditAction();

            $row = $actions->getRow();
            $isAction = false;

            if ($grid_type == 1) {
                $op_name = '取消关联';
            } else {
                if (isset($row['home_column_id'])) {
                    $isAction = true;
                    $op_name = '已关联';
                } else {
                    $op_name = '关联';
                }
            }

            $actions->add(Grid\Actions\ActionButton::make($op_name)->order(3)
                ->beforeEmit("tableSetLoading", true)
                ->successEmit("tableReload")
                ->afterEmit("tableSetLoading", false)
                ->handler(Grid\Actions\ActionButton::HANDLER_REQUEST)
                ->uri('/admin-api/home/column/relation?grid_type=' . $grid_type . '&home_column_id=' . $home_column_id . '&column_id={id}')
                ->disabled($isAction)
                ->message('确认' . $op_name)
            );

        })->actionWidth(30);

        $grid->dataUrl("admin-api/home/column/relation_grid/{$home_column_id}?grid_type=" . $grid_type);

        return $grid;
    }

    public function relation()
    {
        $grid_type = request('grid_type');
        $home_column_id = request('home_column_id');
        $column_id = request('column_id');
        if (empty($grid_type) || empty($home_column_id) || empty($column_id)) {
            return \Admin::responseError('关联失败，参数异常');
        }

        if ($grid_type == 1) {
            DB::table('home_column_ids')
                ->where('home_column_id', $home_column_id)
                ->where('id', $column_id)->delete();
            $data['action']['emit'] = 'tableReload';
            return \Admin::response($data, '取消关联成功');
        } else if ($grid_type == 2) {
            $count = DB::table('home_column_ids')
                ->where('home_column_id', $home_column_id)
                ->where('column_id', $column_id)->count('id');

            if ($count > 0) {
                return \Admin::responseError('专辑已关联');
            } else {
                $item['home_column_id'] = $home_column_id;
                $item['column_id'] = $column_id;
                $item['updated_at'] = date('Y-m-d H:i:s', time());
                $item['created_at'] = date('Y-m-d H:i:s', time());
                $id = HomeColumnIds::insertGetId($item);
                if ($id) {
                    $data['action']['emit'] = 'tableReload';
                    return \Admin::response($data, '专辑关联成功');
                } else {
                    return \Admin::responseError('专辑关联失败，操作异常');
                }
            }
        }

        return \Admin::responseError('操作异常');


    }
}

