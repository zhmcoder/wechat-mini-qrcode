<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Components\form\MultiItem;
use Andruby\DeepAdmin\Components\form\RowMulti;
use Andruby\DeepAdmin\Components\widgets\AddRow;
use Andruby\DeepAdmin\Components\widgets\Column;
use Andruby\DeepAdmin\Controllers\ContentController;
use App\Admin\Models\AppInfo;
use Illuminate\Http\Request;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Components\Form\Radio;
use SmallRuralDog\Admin\Components\Widgets\Card;
use SmallRuralDog\Admin\Components\Widgets\Dialog;
use SmallRuralDog\Admin\Components\Widgets\Divider;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;
use Andruby\DeepAdmin\Models\EntityField;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Grid\Tools\ToolButton;
use SmallRuralDog\Admin\Layout\Content;
use SmallRuralDog\Admin\Layout\Row;

class SelectTestController extends ContentController
{
    private $createUrl = "/seelct/list";

    protected function create_url()
    {
        return $this->createUrl;
    }

    protected function grid_list(Grid $grid): Grid
    {
        $grid->addDialogForm($this->form()->action('?save/index')->isDialog());
        $grid->editDialogForm($this->form(true)->action('?save/index')->isDialog());
        $grid->quickFilter()->filterKey('status')->defaultValue('2')
            ->quickOptions([
                Radio::make('1', '正常'),
                Radio::make('2', '过期'),
                Radio::make('3', '审核')]);
        $grid->filterFormCenter();
        return $grid;
    }

    protected function grid_toolbars($toolbars)
    {
        $uri = explode('?', request()->getRequestUri());
        $params = isset($uri[1]) ? $uri[1] . '&' : '';

        $toolbars->addRight(
            ToolButton::make("导出")
                ->icon("el-icon-plus")
                ->requestMethod('get')
                ->isFilterFormData(true)
                ->handler(ToolButton::HANDLER_LINK)
                ->uri(route('home.column.export'))
        );

        /*$toolbars->addLeft(
            RadioGroup::make('desc', [Radio::make('desc', 'DESC'), Radio::make('asc', 'ASC')])
        );*/
    }

    protected function form($isEdit = 0): Form
    {
        $this->remoteUrl = config('admin.route.api_prefix') . '/select/related_select';
        $form = new Form(new \Andruby\DeepAdmin\Models\Content());
//        $form->action($form->getAction() . '?entity_id=60');
        $form->actionParams(['entity_id' => 60]);
        $form->isDialog();

        $val = EntityField::query()->find(136);
        $form->item('test_select', '下拉测试')
            ->component(Select::make()->filterable()
                ->isRelatedSelect(true)
                ->relatedSelectRef('select_related')
                ->ref('test_select')
                ->options($this->_selectOptionTable($val)))
            ->inputWidth(10);

        $options = [];
        $val = EntityField::query()->find(137);
        $form->item('select_related', '下拉关联')
            ->component(
                Select::make()->options($options)
                    ->extUrlParams(['val' => $val])
                    ->remote($this->remoteUrl)
                    ->isRelatedSelect(true)
                    ->relatedSelectRef('test_r_r')
                    ->relatedComponents(['tes_un_sd', 'tes_un'])
                    ->clearable()->placeholder('请输入查询')
            )
            ->inputWidth(10)->vif('test_select', '[1,2]');

        $options = [];
        $this->remoteUrl = config('admin.route.api_prefix') . '/select/related_select_2';
        $val = EntityField::query()->find(139);
        $form->item('test_r_r', '下拉关联2')
            ->component(
                Select::make()->options($options)
                    ->extUrlParams(['val' => $val])
                    ->remote($this->remoteUrl)
                    ->clearable()->placeholder('请输入查询')
            )
            ->inputWidth(10);

        $val = EntityField::query()->find(126);
        $form->item('tes_un_sd', '关联输入')
            ->component(Input::make())
            ->inputWidth(10);
        $val = EntityField::query()->find(126);
        $form->item('tes_un', '关联输入')
            ->component(Input::make())
            ->inputWidth(10);

        $data = [
            'is_title' => 1,
            'attr_names' => [
                [
                    'label' => '物料',
                    'prop' => 'material_id',
                    'type' => 'select',
                    'options' => ['11' => 12],
                    'value' => 12,
                    'width' => 250,
                ],
                [
                    'label' => '申领数量',
                    'prop' => 'apply_num',
                    'type' => 'number',
                    'value' => 1,
                    'precision' => 0,
                    'width' => 120,
                ],
            ],

        ];
        $tableData[] = ['material_id' => 'qqq'];
        $tableData[] = ['material_id' => 'qqq1'];
        $tableData[] = ['material_id' => 'qqq2'];
        $form->item('add_row', '行操作')
            ->component(AddRow::make()->show_index(true)
                ->index_after('名')->index_prefix('第')->index_width(50)
                ->index_model(2)
                ->data($tableData)
                ->add_column(Column::make()
                    ->label('test')->value('11')
                    ->component(Input::make())
                    ->prop('material_id')->width(100)))
            ->style("margin-top:10px");

//        $form->item('test_left', ' ')
////            ->topComponent(Divider::make('场次规则')->style('width:100px;margin-left:100px'))
////            ->component(Input::make()->ref('qq1_num')->style('width:60px'))
////            ->componentLeftComponent(Text::make('测试左')
//            ->componentTopComponent(Divider::make('场次规则'))
//            ->component(function () {
//                $content = new Content();
//                $content->style("width:80%");
//                $content->row(function (Row $row) {
//                    $row->column(4, Input::make()->ref('qq_num1')->style('width:60px'));
//                    $row->column(2, Text::make('='));
//                    $row->column(4, Input::make()->ref('qq_num2')->style('width:60px'));
//                    $row->column(3, Text::make('次'));
//                });
//                return $content;
//            })->inputWidth(15);
//        $form->item('test_left', ' ')
////            ->topComponent(Divider::make('场次规则')->style('width:100px;margin-left:100px'))
////            ->component(Input::make()->ref('qq1_num')->style('width:60px'))
////            ->componentLeftComponent(Text::make('测试左')
//            ->componentTopComponent(Divider::make('场次规则'))
//            ->component(RowMulti::make()
//                ->addComponents(MultiItem::make()->prop('yytt')->label('123')->afterLabelStyle('display:-moz-inline-box;display:inline-block;width:150px; ')->labelStyle('display:-moz-inline-box;display:inline-block;width:150px; ')->afterLabel('after')
//                    ->component(Input::make()->style('width:50px')))
//                ->addComponents(MultiItem::make()->prop('qqww')->label('qqww')
//                    ->component(Input::make()->style('width:50px'))))
//            ->inputWidth(15);
//
        /*$form->item('sale_unit', '售卖单位')
            ->component(Content::make()->className('m-10')
                ->row(function (Row $row) {
                    $row->gutter(10);
                    $row->column(24, Card::make()->showHeader(false)
                        ->style('width:400px;')->bodyStyle('padding:0px;')
                        ->content($this->sale_unit_grid())
                    );
                })
            );*/

        $form->saving(function (Form $form) {
            return $this->saving_event($form);
        });

        $form->saved(function (Form $form) {
            return $this->saved_event($form);
        });

        $form->editQuery(function ($form, $editData) {
            $tableData[] = ['material_id' => 'zhmzhm1'];
            $tableData[] = ['material_id' => 'zhmzhm2'];
            $form->editData["add_row"] = $tableData;
        });


        return $form;
    }

    public function related_select(Request $request)
    {
        $select_related = $request->input('select_related');
        $data = [
            'data' => [
                'total' => 1,
                'data' => [['value' => 1, 'type' => "defalut",
                    'label' => 'news one ' . $select_related,
                    'tes_un_sd' => 'news one ' . $select_related,
                    'tes_un' => '1 news one ' . $select_related
                ],
                    ['value' => 2, 'type' => "defalut",
                        'label' => 'news two ' . $select_related,
                        'tes_un_sd' => 'news two ' . $select_related,
                        'tes_un' => '2 news one ' . $select_related
                    ]
                ],
            ],
        ];
        return $data;
    }

    public function related_select_2(Request $request)
    {
        $select_related = $request->input('test_r_r');
        $data = [
            'data' => [
                'total' => 1,
                'data' => [['value' => 1,
                    'label' => '2 news one ' . $select_related,
                ],
                    ['value' => 2,
                        'label' => '2 news two ' . $select_related,
                    ],
                    ['value' => 3,
                        'label' => '2 news three ' . $select_related,
                    ]
                ],
            ],
        ];
        return $data;
    }

    /*
     * 售卖单位列表
     * */
    public function sale_unit_grid(): Grid
    {
        $grid = new Grid(new AppInfo());

        $grid->dialogForm($this->sale_unit_form()->isDialog()->className('p-15'));

        $grid->pageBackground()
            ->defaultSort('id', 'desc', 'id')
            ->stripe(true)->fit(true)->showHeader(false)
            ->emptyText("暂无数据");

        $grid->perPage(env('PER_PAGE', 15));
        $grid->height(200);

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->addRight(Grid\Tools\ToolButton::make("添加")
                ->type('info')
                ->requestMethod('get')
                ->isFilterFormData(true)
                ->beforeEmit("tableSetLoading", true)
                ->successEmit("tableReload")
                ->afterEmit("tableSetLoading", false)
                ->handler(Grid\Actions\ActionButton::HANDLER_ROUTE)
                ->dialog(function (Dialog $dialog) {
                    $dialog->title('添加');
                    $dialog->slot(function (\SmallRuralDog\Admin\Layout\Content $content) {
                        $content->className('m-10')
                            ->row(function (Row $row) {
                                $row->gutter(10);
                                $row->column(24, $this->sale_unit_form());
                            });
                    });
                })
            );
        });

        $grid->column('name', "名称");

        $grid->dataUrl("admin-api/select/sale_unit_grid");

        return $grid;
    }

    /*
     * 栏目表单
     * */
    protected function sale_unit_form(): Form
    {
        $form = new Form(new AppInfo());
        $form->style('width:100%;');

        $form->isDialog();

        $form->action('admin-api/select/sale_unit_save');

        $form->item('name', '名称')
            ->component(Input::make()->placeholder('请输入名称')->maxlength(10)->showWordLimit())
            ->inputWidth(12)
            ->required(true, 'string');

        return $form;
    }

    /*
     * 售卖单位添加
     * */
    public function sale_unit_save()
    {
        $name = request('name');

        if (empty($name)) {
            return \Admin::responseError('参数错误');
        }

        $data = [
            'name' => $name,
        ];
        AppInfo::query()->create($data);

        $data['action']['emit'] = 'tableReload';
        return \Admin::response($data, '操作成功');
    }
}

