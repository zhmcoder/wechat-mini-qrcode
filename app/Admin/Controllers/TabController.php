<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Controllers\ContentController;
use Andruby\DeepAdmin\Models\Brand;
use Andruby\DeepAdmin\Models\Content;
use Andruby\DeepAdmin\Models\EntityField;
use App\Admin\Models\AppInfo;
use SmallRuralDog\Admin\Components\Attrs\SelectOption;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Grid;
use SmallRuralDog\Admin\LeftGrid;
use SmallRuralDog\Admin\TabForm;

class TabController extends ContentController
{
    private $createUrl = "/tab/list/create?";

    protected function create_url()
    {
        return $this->createUrl;
    }

    protected function grid_action(Grid\Actions $actions)
    {
        $actions->setDeleteAction(new Grid\Actions\DeleteDialogAction());
    }

    protected function grid()
    {
        $grid = new LeftGrid(new AppInfo());

        $action = config('admin.route.api_prefix') . '/tab/getFilter?type=1';
        $grid->filter(function (Grid\Filter $filter) use ($action) {
            $filter->leftAction($action)->reload('left_filter')->equal('top', '顶部搜索')
                ->component(Select::make()->options(function () {
                    return Brand::query()->get()->map(function ($item) {
                        return SelectOption::make($item->id, $item->name);
                    })->all();
                }));
        });

        return $grid;
    }

    public function getFilter(LeftGrid $grid)
    {
        $top = request('top', 1);
        $grid->leftFilter(function (Grid\LeftFilter $leftFilter) use ($top) {
            $leftFilter->equal('left_' . $top, '左侧搜索' . $top)->component(Select::make()->options(function () {
                return Brand::query()->get()->map(function ($item) {
                    return SelectOption::make($item->id, $item->name);
                })->all();
            }));
        });

        return $grid->getLeftFilter();
    }

    protected function form($isEdit = 0)
    {
        $form = new TabForm(new Content('app_info'));
        $form->getActions()->buttonCenter();
        $form->hideTab(false);
        $form->tabPosition('left');

        $form->actionParams(['entity_id' => 50, 'name' => 'zhm']);
        $form->labelWidth('150px');
        $form->labelPosition('right')->statusIcon(true);
        $form->tabValue('测试');

        $val = EntityField::query()->find(136);
        $form->item('select_related', '下拉测试')
            ->component(Select::make()->filterable()
                ->isRelatedSelect(true)
                ->relatedSelectRef('os_type')
                ->ref('select_related')
                ->options($this->_selectOptionTable($val)))
            ->inputWidth(10);

        $options = [];
        $this->remoteUrl = config('admin.route.api_prefix') . '/select/related_select';
        $action = config('admin.route.api_prefix') . '/tab/getTab';
        $form->item('os_type', '应用系统')
            ->component(
                Select::make()->options($options)->clearable()
                    ->remote($this->remoteUrl)
                    ->ref('os_type')
                    ->isTab()->tabAction($action)
                    ->placeholder('请输入查询')
            )
            ->inputWidth(10);

        $form->item('test', '测试')
            ->component(Input::make(''))
            ->inputWidth(6)
            ->tab('测试');

        return $form;
    }

    public function getTab()
    {
        $form = new TabForm(new Content('app_info'));
        $form->getActions()->buttonCenter();
        $form->hideTab(false);
        $form->tabPosition('left');

        $form->actionParams(['entity_id' => 50, 'name' => 'zhm']);
        $form->labelWidth('150px');
        $form->labelPosition('right')->statusIcon(true);

        $val = EntityField::query()->find(136);
        $form->item('select_related', '下拉测试')
            ->component(Select::make()->filterable()
                ->isRelatedSelect(true)
                ->relatedSelectRef('os_type')
                ->ref('select_related')
                ->options($this->_selectOptionTable($val)))
            ->inputWidth(10);

        $options = [];
        $this->remoteUrl = config('admin.route.api_prefix') . '/select/related_select';
        $action = config('admin.route.api_prefix') . '/tab/getTab';
        $form->item('os_type', '应用系统')
            ->component(
                Select::make()->options($options)->clearable()
                    ->remote($this->remoteUrl)
                    ->ref('os_type')
                    ->isTab()->tabAction($action)
                    ->placeholder('请输入查询')
            )
            ->inputWidth(10);

        $form->item('test1', '测试1')
            ->component(Input::make(''))
            ->inputWidth(6)
            ->tab('测试1');

        $form->item('test2', '测试2')
            ->component(Input::make(''))
            ->inputWidth(6)
            ->tab('测试2');
        $value = request('value', 1);
        if ($value == 2) {
            $form->item('test_2', '测试2')
                ->component(Input::make(''))
                ->inputWidth(6)
                ->tab('测试');
        }
        if ($value == 2) {
            $form->item('test_3', '测试2')
                ->component(Input::make(''))
                ->inputWidth(6)
                ->tab('测试3');
        }
        return $form;
    }
}

