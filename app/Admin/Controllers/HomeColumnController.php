<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Components\Grid\SortEdit;
use App\Models\HomeColumn;
use App\Models\HomeColumnIds;
use Illuminate\Support\Facades\DB;
use SmallRuralDog\Admin\Components\Widgets\Card;
use Andruby\DeepAdmin\Controllers\ContentController;
use SmallRuralDog\Admin\Grid;
use Andruby\DeepAdmin\Models\Content;
use SmallRuralDog\Admin\Layout\Row;
use Illuminate\Database\Query\JoinClause;

// 货架关联
class HomeColumnController extends ContentController
{
    protected function grid_action(Grid\Actions $actions)
    {
        $actions->add(Grid\Actions\ActionButton::make('关联商品')
            ->handler(Grid\Actions\ActionButton::HANDLER_ROUTE)
            ->uri('/home/column/relation_grid/{id}?timestamp=' . time()));
    }

    public function relation_grid(\SmallRuralDog\Admin\Layout\Content $content, $home_column_id = null)
    {
        $grid_type = request('grid_type');

        $grid_left = $this->column_grid(1, 'home_column_ids', $home_column_id);
        $grid_right = $this->column_grid(2, 'goods', $home_column_id);

        $homeColumnInfo = HomeColumn::query()->find($home_column_id);

        $content->row(function (Row $row) use ($grid_left, $grid_right, $homeColumnInfo) {
            $row->gutter(0)->className('mt-10');
            $row->column(12, Card::make()->header('货架：' . $homeColumnInfo['name'] . '，已关联商品')->bodyStyle('padding:0px;margin_left:100px;')->content(
                $grid_left
            ));
            $row->column(12, Card::make()->header('商品列表')->bodyStyle('padding:0px;')->content(
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

        $grid->quickSearch(['name'])->quickSearchPlaceholder("商品名称");

        if ($grid_type == 1) {
            $grid->model()->where('home_column_id', $home_column_id)
                ->join('goods', function (JoinClause $join) use ($table_name) {
                    $join->on($table_name . '.column_id', '=', 'goods.id')
                        ->where('on_shelf', 1);
                })->select(['home_column_ids.id as id', 'home_column_ids.sort as sort', 'goods.name as name']);

            $grid->defaultSort('sort', 'asc');
            $grid->column('id', "ID")->width(80)->sortable();
        } else {
            $grid->model()->where('on_shelf', 1);
        }

        $grid->column('name', "商品名称")->sortable();

        if ($grid_type == 1) {
            $grid->column('sort', '排序')->component(
                SortEdit::make()->action(config('admin.route.api_prefix') . '/entities/content/grid_sort_change?entity_id=55')
            )->width(100)->sortable();
        }

        $grid->actions(function (Grid\Actions $actions) use ($grid_type, $home_column_id) {
            $actions->hideDeleteAction()->hideEditAction();

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
                return \Admin::responseError('已关联');
            } else {
                $item['home_column_id'] = $home_column_id;
                $item['column_id'] = $column_id;
                $item['updated_at'] = date('Y-m-d H:i:s', time());
                $item['created_at'] = date('Y-m-d H:i:s', time());
                $id = HomeColumnIds::insertGetId($item);
                if ($id) {
                    $data['action']['emit'] = 'tableReload';
                    return \Admin::response($data, '关联成功');
                } else {
                    return \Admin::responseError('关联失败，操作异常');
                }
            }
        }
        return \Admin::responseError('操作异常');
    }
}

