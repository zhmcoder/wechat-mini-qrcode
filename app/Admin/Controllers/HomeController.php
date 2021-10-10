<?php

namespace App\Admin\Controllers;

use Andruby\DeepAdmin\Components\Antv\DualAxes;
use Andruby\DeepAdmin\Components\Antv\Graph;
use Andruby\DeepAdmin\Components\Echarts\BarChart;
use Andruby\DeepAdmin\Components\Echarts\DisplayList;
use Andruby\DeepAdmin\Components\Echarts\GaugeChart;
use Andruby\DeepAdmin\Components\Echarts\LineChart;
use Andruby\DeepAdmin\Components\Echarts\LiquidFillChart;
use Andruby\DeepAdmin\Components\Echarts\MarkLineChart;
use Andruby\DeepAdmin\Components\Echarts\MixChart;
use Andruby\DeepAdmin\Components\Echarts\NormalBarChart;
use Andruby\DeepAdmin\Components\Echarts\NumDisplayH;
use Andruby\DeepAdmin\Components\Echarts\NumDisplayV;
use Andruby\DeepAdmin\Components\Echarts\PieChart;
use Andruby\DeepAdmin\Components\Echarts\PropertyList;
use Andruby\DeepAdmin\Components\Echarts\RatePieChart;
use Andruby\DeepAdmin\Components\Echarts\SectionLineChart;
use Andruby\DeepAdmin\Components\Gantt\Chart;
use Andruby\DeepAdmin\Components\Gantt\GSTC;
use Andruby\DeepAdmin\Components\Gantt\Lists;
use SmallRuralDog\Admin\Components\Antv\Area;
use SmallRuralDog\Admin\Components\Antv\Column;
use SmallRuralDog\Admin\Components\Antv\Line;
use SmallRuralDog\Admin\Components\Antv\StepLine;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Components\Widgets\Alert;
use SmallRuralDog\Admin\Components\Widgets\Card;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Layout\ChartCard;
use SmallRuralDog\Admin\Layout\Content;
use SmallRuralDog\Admin\Layout\Row;

class HomeController extends AdminController
{
    public function index(Content $content)
    {
//        $content->isMsgDialogShow(true);

        //甘特图数据格式
        $showRows = [];
        $showColumns = [];
        $licenseKey = '====BEGIN LICENSE KEY====\nUDm9p01o1K+pcbY4Uo2Q1EcoorGkjTljUZRk6sp7xY+mU65GOdbM4e/c81TbAI91kLxpc2RhP79Vz3+MqCcpDtOa3MnhmDkp48wdithQzzvMS1024VgAsd+t+1e/R6XIZ+hUkX38WCDpdqbuftXxl+iA3bwZp2ubiVbMXblgFi8Gy6c6Jy/3VCxgprVh+ZBA47caDq7jw1uaR++dtHDCXiJgtEkEypt2DGWnbAXvgC5zNJ8RnM0D/mabtba/FKSzKHApe7j4GyX/8F1HyPgMjTo48weQ4ttP0FOA5enrG7LNek0AgYEnmhWfoNBivy8qCe7vtfKqL6JJdwJjldY5gQ==||U2FsdGVkX18tsWTfvqFEfd1sLZFgEemTKCdPwa/VxOZkNi/KDkAe5n799e3QPHn5stHwuJHZDwTrQFj8QU4msiSMmd6AK2wty3n7Nwt5Hnk=\nOTGTCZeoxBVOvAmqAFgCKX4cV2ZqJSBsTmYYqtmshDvqQ0RkpjmWoBArZOILyZ5lS5ZEj+cLWHsEnnrRG28wH3k96gnx4gmUnu3ks0k4EEEs7pZijHMp0yDlUtTimo6zllRkNOtaqVXz8UM1oAspuroh0hEOgMOup5/JS525r6n+85wNbPbEyfEm0Xo+KWxE/QloHtTivE3AsNNZLtwWCnH1S5jQTDTjGjenkEKZeBVSelR4E6UW5vr0NG57N0Rc7H9gaFIiEYxDu9i7l3LGDWlpVla997K4FmnuUJH4iA8mLH79WKurSkNTjKC7ys/TeH65sM7dgpuoEJpq5uO6pA==\n====END LICENSE KEY====';

        //说明：这个在在甘特图前面增加的数据展示，id表示行,示例如下
        //列的定义
        $showColumns[] = Lists\Columns\Data::make()->id('id')->width(60)->data('id')->header(Lists\Columns\Header::make()->content('ID'));
        $showColumns[] = Lists\Columns\Data::make()->id('label')->width(60)->data('label')->header(Lists\Columns\Header::make()->content('label'));
        $showColumns[] = Lists\Columns\Data::make()->id('progress')->width(60)->data('progress')->header(Lists\Columns\Header::make()->content('进度'));

        //数据
        //[
        //  {
        //      id: '1',
        //    label: 'Row 1',
        //  },
        //  {
        //      id: '2',
        //    label: 'Row 2',
        //  },
        //]
        for ($i = 0; $i < 10; $i++) {
            $item['id'] = $i;
            $item['label'] = "Row " . $i;
            $item['progress'] = 11;
            $showRows[] = $item;
        }
        //itemsData，展示的项目内容，示例格式如下：
        //[
        //  {
        //      id: '1',
        //    label: 'Item 1',
        //    rowId: '1',
        //    time: {
        //      start: GSTC.api.date('2020-01-01').startOf('day').valueOf(),
        //      end: GSTC.api.date('2020-01-02').endOf('day').valueOf(),
        //    },
        //  },
        //  {
        //      id: '2',
        //    label: 'Item 2',
        //    rowId: '1',
        //    time: {
        //      start: GSTC.api.date('2020-02-01').startOf('day').valueOf(),
        //      end: GSTC.api.date('2020-02-02').endOf('day').valueOf(),
        //    },
        //  },
        //  {
        //      id: '3',
        //    label: 'Item 3',
        //    rowId: '2',
        //    time: {
        //      start: GSTC.api.date('2020-01-15').startOf('day').valueOf(),
        //      end: GSTC.api.date('2020-01-20').endOf('day').valueOf(),
        //    },
        //  },
        //];
        $itemsData = [];
//        const items = {};
//        let start = GSTC.api.date().startOf('day').subtract(6,'day');
//        for(let i =0;i<15;i++){
//        const id = i.toString();
//        const rowId = i.toString();
//        start = start.add(1,'day');
//        items[id] = {
//            id,
//                label:`Item ${i}`,
//                rowId,
//                time:{
//                start: start.valueOf(),
//                    end: start.add(1,'day').endOf('day').valueOf()
//                }
//            }
//        }

        $item_start = time() * 1000 - 12 * 60 * 60 * 1000;
        for ($i = 0; $i < 15; $i++) {
            $id = $i;
            $rowId = $id;
            $item_start = $item_start + 1 * 60 * 60 * 1000;
            $item = Chart\Items::make()->id($id)->label('item-1 ' . $id)
                ->rowId($rowId)->start($item_start)->end($item_start + 1 * 60 * 60 * 1000)
                ->style(['background' => 'blue']);

            $itemsData[] = $item;
        }


        //甘特图数据格式
        $content->className('m-10')
            ->row(function (Row $row) {
                $row->gutter(10);
                $row->column(24, Alert::make("你好，同学！！", "欢迎使用 " . env('ADMIN_NAME', 'DeepAdmin'))->showIcon()->closable(false)->type("success"));
            })->row(function (Row $row) use ($licenseKey, $showRows, $itemsData, $showColumns) {
                $row->gutter(10);
                $row->column(24, GSTC::make()->licenseKey($licenseKey)
                    ->lists(Lists::make()->rows($showRows)->columns(Lists\Columns::make()->data($showColumns)))
                    ->chart(Chart::make()->items($itemsData)
                        ->time(Chart\Time::make()->period('hour')->zoom(16)->from(strtotime('2021-06-22 00:00:00') * 1000)->to(strtotime('2021-06-22 23:59:59') * 1000))));
            })->row(function (Row $row) {
                $row->gutter(10);
                $row->column(12, ChartCard::make()->title('测试标题')
                    ->addFilter(Select::make('2')->clearable()->filterable()->style("padding-left: 10px;width:150px")->ref('date_time')->placeholder('请选择日期')
                        ->options([['value' => '1', 'label' => '2020-12-12'], ['value' => '2', 'label' => '2021-1-1']]))
                    ->addFilter(Select::make('')->clearable()->filterable()->style("padding-left: 10px;width:150px")->ref('device_id')->placeholder('请选择设备')->options([['value' => '1', 'label' => '2020-12-12']]))
                    ->data_url(config('admin.route.api_prefix') . '/home/chart_data')
                    ->chart(RatePieChart::make()
                        ->data(function () {
                            // $data_str = '{"name": {"age": "11"}}';

                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "title": {
                                  "text": "时间稼动率",
                                  "fontSize": 20,
                                  "color": "#3E3E3E"
                                },
                                "subtitle": {
                                  "text": "31.62",
                                  "fontSize": 30,
                                  "color": "#3E3E3E"
                                },
                                "series": {
                                    "color": ["#6573FF", "#2AC7F6"],
                                    "data": 31.62
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        }))
                );
            })->row(function (Row $row) {
                $row->gutter(10);
                $row->column(24, Card::make()->showHeader(false)->header('测试标题')->bodyStyle(['padding' => '0'])->content(
                    Graph::make()->data(function () {
                        $data = [
                            'nodes' => [
                                [
                                    'id' => 'node1',
                                    'x' => 40,
                                    'y' => 60,
                                    'width' => 80,
                                    'height' => 40,
                                    'label' => '质检'
                                ],
                                [
                                    'id' => 'node2',
                                    'x' => 160,
                                    'y' => 60,
                                    'width' => 80,
                                    'height' => 40,
                                    'label' => '首检',
                                ],

                                [
                                    'id' => 'node3',
                                    'x' => 280,
                                    'y' => 40,
                                    'width' => 120,
                                    'height' => 80,
                                    'label' => '填表,出cpk',
                                    'shape' => 'polygon',
                                    'attrs' => [
                                        'body' => [
                                            'fill' => '#efdbff',
                                            'stroke' => '#9254de',
                                            'refPoints' => '0,10 10,0 20,10 10,20',
                                        ]
                                    ]
                                ]
                            ],
                            'edges' => [
                                [
                                    'source' => 'node1',
                                    'target' => 'node2',
                                ],
                                [
                                    'source' => 'node2',
                                    'target' => 'node3',
                                ]
                            ]
                        ];
                        return $data;
                    })->config(function () {
                        return [
                            'width' => 800,
                            'height' => 400,
                        ];
                    })
                ));
            });

        return $content;
    }

    public function home(Content $content)
    {
        $content->className('m-10')
            ->row(function (Row $row) {
                $row->gutter(10);
                $row->column(24, Alert::make("你好，同学！！", "欢迎使用 " . env('ADMIN_NAME', 'DeepAdmin'))->showIcon()->closable(false)->type("success"));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    Line::make()
                        ->data(function () {
                            $data = collect();
                            for ($year = 2010; $year <= 2020; $year++) {
                                $data->push([
                                    'year' => (string)$year,
                                    'type' => '小红',
                                    'value' => rand(100, 1000)
                                ]);
                                $data->push([
                                    'year' => (string)$year,
                                    'type' => '小白',
                                    'value' => rand(100, 1000)
                                ]);
                            }
                            return $data;
                        })
                        ->config(function () {
                            return [
                                'title' => [
                                    'visible' => true,
                                    'text' => '折线图',
                                ],
                                'description' => [
                                    'visible' => true,
                                    'text' => '他们最常用于表现趋势和关系，而不是传达特定的值。',
                                ],
                                'seriesField' => 'type',
                                'smooth' => true,
                                'xField' => 'year',
                                'yField' => 'value'
                            ];
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    Area::make()
                        ->data(function () {
                            $data = collect();
                            for ($year = 2010; $year <= 2020; $year++) {
                                $data->push([
                                    'year' => (string)$year,
                                    'type' => '小红面积',
                                    'value' => rand(100, 1000)
                                ]);
                                $data->push([
                                    'year' => (string)$year,
                                    'type' => '小白面积',
                                    'value' => rand(100, 1000)
                                ]);
                            }
                            return $data;
                        })
                        ->config(function () {
                            return [
                                'title' => [
                                    'visible' => true,
                                    'text' => '面积图',
                                ],
                                'description' => [
                                    'visible' => true,
                                    'text' => '他们最常用于表现趋势和关系，而不是传达特定的值。',
                                ],
                                'seriesField' => 'type',
                                'smooth' => false,
                                'xField' => 'year',
                                'yField' => 'value'
                            ];
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    StepLine::make()
                        ->data(function () {
                            $data = collect();
                            for ($year = 2010; $year <= 2020; $year++) {
                                $data->push([
                                    'year' => (string)$year,
                                    'type' => '小红面积',
                                    'value' => rand(100, 1000)
                                ]);
                            }
                            return $data;
                        })
                        ->config(function () {
                            return [
                                'title' => [
                                    'visible' => true,
                                    'text' => '阶梯图',
                                ],
                                'description' => [
                                    'visible' => true,
                                    'text' => '阶梯线图用于表示连续时间跨度内的数据',
                                ],
                                'smooth' => false,
                                'xField' => 'year',
                                'yField' => 'value'
                            ];
                        })
                ));
//                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
//                    GoodsSku::make('12')
//                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    LineChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "400px",
                                "xAxisData": ["热压产线003", "热压产线004"],
                                "series": {
                                    "radius": ["60%", "86%"],
                                    "color": "#037DF6",
                                    "data": [6098, 7],
                                    "areaColor": ["rgba(3, 130, 246, 0.5)", "rgba(3, 184, 246, 0)"]
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    PieChart::make()
                        ->data(function () {
                            // $data_str = '{"name": {"age": "11"}}';

                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "showTooltip": true,
                                "title": {
                                  "text": "",
                                  "fontSize": 40,
                                  "color": "#3E3E3E",
                                  "left": "24%",
                                  "top": "center"
                                },
                                "subtitle": {
                                  "text": "",
                                  "fontWeight": "normal",
                                  "fontSize": 14,
                                  "color": "#9CA4B2"
                                },
                                "legend": {
                                  "orient": "vertical",
                                  "right": "10%",
                                  "y": "middle",
                                  "data": ["正常工作", "非故障停机", "故障停机"],
                                  "color": "#3E3E3E",
                                  "fontSize": 16
                                },
                                "series": {
                                    "radius": "60%",
                                    "color": ["#FCB321", "#F55273", "#2ABAEC"],
                                    "center": ["32%", "50%"],
                                    "data": [{
                                        "name": "正常工作",
                                        "value": 44
                                    }, {
                                        "name": "非故障停机",
                                        "value": 11
                                    }, {
                                        "name": "故障停机",
                                        "value": 12
                                    }]
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    BarChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "legend": {
                                  "orient": "vertical",
                                  "right": "10%",
                                  "y": "middle",
                                  "data": ["正常工作", "非故障停机", "故障停机"],
                                  "color": "#3E3E3E",
                                  "fontSize": 16
                                },
                                "series": {
                                    "maxData": [5, 5, 5],
                                    "data": [{
                                        "name": "计划完成量",
                                        "value": 1,
                                        "color": "#2CC1F5",
                                        "colorEnd": "#6177FE"
                                      }, {
                                        "name": "一次良品数量",
                                        "value": 3,
                                        "color": "#71EDF9",
                                        "colorEnd": "#3ECCF9"
                                      }, {
                                        "name": "次品量",
                                        "value": 5,
                                        "color": "#FF829B",
                                        "colorEnd": "#F65374"
                                    }]
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    GaugeChart::make()
                        ->data(function () {
                            // $data_str = '{"name": {"age": "11"}}';

                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "series": {
                                    "areaColor": ["#8CD6FF", "#22A9FF", "#037DF6"],
                                    "data": ["83.1"],
                                    "itemWidth": 30
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    MixChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "legend": {
                                  "data": [{"name": "生产数量"}, {"name": "异常时间率"}, {"name": "一次良品率"}]
                                },
                                "xAxisData": ["6666666", "4321", "8910", "3456", "456", "987", "4324", "42342"],
                                "yAxisName": "数量（个）",
                                "series": [{
                                    "name": "生产数量",
                                    "data": ["24", "10", "60", "10", "10", "10", "6060", "1111", "5", "5", "10", "10", "10", "5", "10", "10"],
                                    "type": "bar",
                                    "color": "#6177FE",
                                    "colorEnd": "#2CC1F5"
                                }, {
                                    "name": "异常时间率",
                                    "data": [68.38, 0, 21.24, 0, 35.03, 20.42, 0, 32.5, 0, 42.49, 47.72, 2.2, 0, 0, 3.25, 30.44],
                                    "type": "bar",
                                    "color": "#F65373",
                                    "colorEnd": "#FF8EA5"
                                }, {
                                    "name": "一次良品率",
                                    "data": [100, 75, 100, 80, 70, 80, 1.65, 81.01, 40, 40, 40, 80, 60, 100, 80, 70],
                                    "type": "line",
                                    "color": "#6177FE",
                                    "colorEnd": "#2CC1F5"
                                }],
                                "unit": "%"
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    RatePieChart::make()
                        ->data(function () {
                            // $data_str = '{"name": {"age": "11"}}';

                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "title": {
                                  "text": "时间稼动率",
                                  "fontSize": 20,
                                  "color": "#3E3E3E"
                                },
                                "subtitle": {
                                  "text": "31.62",
                                  "fontSize": 30,
                                  "color": "#3E3E3E"
                                },
                                "series": {
                                    "color": ["#6573FF", "#2AC7F6"],
                                    "data": 31.62
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    LiquidFillChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "series": {
                                    "name": "生产数量",
                                    "data": 30,
                                    "background": "#F5F7FA",
                                    "color": "#3E3E3E",
                                    "itemColor": "#4BB9FF",
                                    "radius": "90%"
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    SectionLineChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "400px",
                                "xAxisData": ["11", "22", "33", "44", "55", "66", "77", "88", "99", "10", "11", "12"],
                                "color": ["#6eaaee","#32CD32"],
                                "legend": {
                                    "show": true,
                                    "data": ["test001","test002"]
                                },
                                "series": [{
                                    "type": "line",
                                    "name":"test001",
                                    "data": [12.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4, 23.0, 16.5, 12.0, 16.2],
                                    "markLine": {
                                        "silent":true,
                                        "lineStyle":{
                                            "color":"red"
                                        },
                                        "data": [
                                            {
                                                "name":"最低值",
                                                "yAxis":10
                                            },{
                                                "name":"最高值",
                                                "yAxis":20
                                            }
                                        ]
                                    }
                                },{
                                    "type": "line",
                                    "name":"test002",
                                    "data": [2.0, 12.2, 13.3, 24.5, 26.3, 1.2, 10.3, 13.4, 13.0, 26.5, 2.0, 6.2]
                                }],
                                "visualMap": [{
                                    "show": false,
                                    "type": "piecewise",
                                    "seriesIndex": 0,
                                    "pieces": [
                                        {
                                            "gt": 20,
                                            "color": "red"
                                        }, {
                                            "gt": 10,
                                            "lte": 20,
                                            "color": "#6eaaee"
                                        }, {
                                            "gt": 0,
                                            "lte": 10,
                                            "color": "red"
                                        }
                                    ]
                                }, {
                                    "show": false,
                                    "type": "piecewise",
                                    "seriesIndex": 1,
                                    "pieces": [
                                        {
                                            "gt": 20,
                                            "color": "red"
                                        }, {
                                            "gt": 10,
                                            "lte": 20,
                                            "color": "#32CD32"
                                        }, {
                                            "gt": 0,
                                            "lte": 10,
                                            "color": "red"
                                        }
                                    ]
                                }],
                                "dataZoom":[{"start":80,"end":100}]
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    NumDisplayH::make()
                        ->data(function () {
                            $data_str = '[{
                                "color": "#037DF6",
                                "count": 20,
                                "name": "车间总数"
                              }, {
                                "color": "#22A9FF",
                                "count": 21,
                                "name": "产线总数"
                              }, {
                                "color": "#8CD6FF",
                                "count": 49,
                                "name": "设备总数"
                              }]';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    PieChart::make()
                        ->data(function () {
                            // $data_str = '{"name": {"age": "11"}}';

                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "showTooltip": true,
                                "title": {
                                  "text": "90%",
                                  "fontSize": 40,
                                  "color": "#3E3E3E",
                                  "left": "25%",
                                  "top": "center"
                                },
                                "subtitle": {
                                  "text": "设备启用率",
                                  "fontWeight": "normal",
                                  "fontSize": 14,
                                  "color": "#9CA4B2"
                                },
                                "legend": {
                                  "orient": "vertical",
                                  "right": "10%",
                                  "y": "middle",
                                  "data": ["正常工作", "非故障停机", "故障停机"],
                                  "color": "#3E3E3E",
                                  "fontSize": 16
                                },
                                "series": {
                                    "radius": ["60%", "86%"],
                                    "color": ["#FCB321", "#F55273", "#2ABAEC"],
                                    "center": ["32%", "50%"],
                                    "data": [{
                                        "name": "正常工作",
                                        "value": 70
                                    }, {
                                        "name": "非故障停机",
                                        "value": 20
                                    }, {
                                        "name": "故障停机",
                                        "value": 10
                                    }]
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    NumDisplayV::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "100%",
                                "height": "360px",
                                "data": [{
                                    "color": "#037DF6",
                                    "count": 11,
                                    "name": "天"
                                  }, {
                                    "color": "#22A9FF",
                                    "count": 2,
                                    "name": "小时"
                                  }, {
                                    "color": "#8CD6FF",
                                    "count": 12,
                                    "name": "分钟"
                                  }]
                            }';
                            $data = json_decode($data_str, true);
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    DisplayList::make()
                        ->data(function () {
                            // $data_str = '{"name": {"age": "11"}}';

                            $data_str = '[{
                                "name": "编号: 100102",
                                "status": 1
                              }, {
                                "name": "编号: 100103",
                                "status": 2
                              }, {
                                "name": "编号: 100104",
                                "status": 2
                              }, {
                                "name": "编号: 100105",
                                "status": 3
                              }, {
                                "name": "编号: 100106",
                                "status": 1
                              }, {
                                "name": "编号: 100101",
                                "status": 3
                              }, {
                                "name": "编号: 100106",
                                "status": 1
                              }, {
                                "name": "编号: 100101",
                                "status": 3
                              }, {
                                "name": "编号: 100106",
                                "status": 1
                              }, {
                                "name": "编号: 100101",
                                "status": 3
                              }, {
                                "name": "编号: 100106",
                                "status": 1
                              }, {
                                "name": "编号: 100101",
                                "status": 3
                              }]';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    MarkLineChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "xAxisData": ["2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 19:57:43","2020-05-10 20:14:37","2020-05-10 20:14:38","2020-05-10 20:14:39","2020-05-10 20:14:39","2020-05-10 20:14:40","2020-05-10 20:14:42","2020-05-10 20:14:43","2020-05-10 20:14:44","2020-05-10 20:14:45","2020-05-10 20:14:46","2020-05-10 20:14:47","2020-05-10 20:14:48","2020-05-10 20:14:49","2020-05-10 20:14:50","2020-05-10 20:14:51","2020-05-10 20:14:52","2020-05-10 20:14:53","2020-05-10 20:14:54","2020-05-10 20:14:55","2020-05-10 20:14:56","2020-05-10 20:14:57"],
                                "yAxisData": {
                                    "min": 19,
                                    "max": 64
                                },
                                "series": [{
                                    "type": "line",
                                    "data": [{
                                        "value":47.5,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":40,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":44.4,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":47,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":40,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":38,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":47.1,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":38,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":48,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":38,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":43,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":40,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":42,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":46,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":35,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":44,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":47.5,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":38,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":45,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":48.7,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":47.5,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":40,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":44.4,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":58,
                                        "is_warning":1,
                                        "waring_list":[
                                            "1点落在3σ 以外"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":63,
                                        "is_warning":1,
                                        "waring_list":[
                                            "告警值：63，超过最大值：60"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":40,
                                        "is_warning":0,
                                        "waring_list":[],
                                        "itemStyle":{
                                            "color":"#666"
                                        }
                                    },
                                    {
                                        "value":54,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":55,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":38,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":37,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":36,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":35,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":34,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":33,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":32,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续6点递增或递减",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":33,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":32,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":31,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":32,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":31,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    },
                                    {
                                        "value":32,
                                        "is_warning":1,
                                        "waring_list":[
                                            "连续3点中有2点在中心线同一侧的2σ 以外",
                                            "连续5点中有4点在中心线同一侧的1σ 以外",
                                            "连续9点落在中心线同一侧",
                                            "连续8点在中心线两侧，但无一点在1σ 内"
                                        ],
                                        "itemStyle":{
                                            "color":"#c00"
                                        }
                                    }],
                                    "markLine": {
                                        "lineStyle":{
                                            "color":"#c00"
                                        },
                                        "data": [
                                            {
                                                "yAxis":20,
                                                "label":{
                                                    "formatter":"LSL=20"
                                                },
                                                "lineStyle":{
                                                    "type":"solid"
                                                }
                                            },
                                            {
                                                "yAxis":30.2,
                                                "label":{
                                                    "formatter":"LCL=30.2"
                                                }
                                            },
                                            {
                                                "yAxis":42.86,
                                                "label":{
                                                    "formatter":"CL=42.86"
                                                },
                                                "lineStyle":{
                                                    "type":"solid",
                                                    "color":"#04cc68"
                                                }
                                            },
                                            {
                                                "yAxis":55.52,
                                                "label":{
                                                    "formatter":"UCL=55.52"
                                                }
                                            },
                                            {
                                                "yAxis":60,
                                                "label":{
                                                    "formatter":"USL=60"
                                                },
                                                "lineStyle":{
                                                    "type":"solid"
                                                }
                                            }
                                        ]
                                    }
                                }],
                                "visualMap": {
                                    "show": false,
                                    "dimension": 0,
                                    "pieces": [{
                                        "gte": 23,
                                        "lte": 24,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 26,
                                        "lte": 27,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 27,
                                        "lte": 28,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 28,
                                        "lte": 29,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 29,
                                        "lte": 30,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 30,
                                        "lte": 31,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 31,
                                        "lte": 32,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 32,
                                        "lte": 33,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 33,
                                        "lte": 34,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 34,
                                        "lte": 35,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 35,
                                        "lte": 36,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 36,
                                        "lte": 37,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 37,
                                        "lte": 38,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 38,
                                        "lte": 39,
                                        "color": "#c00"
                                    },
                                    {
                                        "gte": 39,
                                        "lte": 40,
                                        "color": "#c00"
                                    }],
                                    "outOfRange": {
                                        "color": "#666"
                                    }
                                }
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    MixChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "legend": {
                                  "data": [{"name": "生产数量"}, {"name": "异常时间率"}, {"name": "一次良品率"}, {"name": "多条折线"}]
                                },
                                "xAxisData": ["6666666", "4321", "8910", "3456", "456", "987", "4324", "42342"],
                                "yAxisName": "数量（个）",
                                "series": [{
                                    "name": "生产数量",
                                    "data": ["24", "10", "60", "10", "10", "10", "6060", "1111", "5", "5", "10", "10", "10", "5", "10", "10"],
                                    "type": "bar",
                                    "color": "#6177FE",
                                    "colorEnd": "#2CC1F5"
                                }, {
                                    "name": "异常时间率",
                                    "data": [68.38, 0, 21.24, 0, 35.03, 20.42, 0, 32.5, 0, 42.49, 47.72, 2.2, 0, 0, 3.25, 30.44],
                                    "type": "bar",
                                    "color": "#F65373",
                                    "colorEnd": "#FF8EA5"
                                }, {
                                    "name": "一次良品率",
                                    "data": [100, 75, 100, 80, 70, 80, 1.65, 81.01, 40, 40, 40, 80, 60, 100, 80, 70],
                                    "type": "line",
                                    "color": "#2CC1F5",
                                    "colorEnd": "#2CC1F5"
                                }, {
                                    "name": "多条折线",
                                    "data": [20, 40, 20, 60, 100, 12, 80, 50, 30, 30, 30, 60, 100, 20, 40, 55],
                                    "type": "line",
                                    "color": "#FF8EA5",
                                    "colorEnd": "#FF8EA5"
                                }],
                                "unit": "%"
                              }';
                            $data = json_decode($data_str, true);
                            // $data['name'] = 'nihao';
                            return $data;
                        })
                ));
                $row->column(12, ChartCard::make()->title('测试标题')
                    ->addFilter(Select::make(null)->name('date_time')->ref('date_time')->placeholder('请选择日期')
                        ->options([['value' => '1', 'label' => '2020-12-12'], ['value' => '2', 'label' => '2021-1-1']]))
                    ->data_url(config('admin.route.api_prefix') . '/home/chart_data')
                    ->chart(NormalBarChart::make()
                        ->data(function () {
                            $data_str = '{
                                "width": "",
                                "height": "360px",
                                "title": "这是图表标题",
                                "legend": {
                                  "data": [{"name": "数量"}]
                                },
                                "xAxisData": ["产线1", "产线2", "产线3"],
                                "yAxisName": "test",
                                "series": [{
                                    "name": "数量",
                                    "data": [12, 5, 8],
                                    "type": "bar",
                                    "color": "#6177FE",
                                    "colorEnd": "#2CC1F5"
                                }],
                                "dataZoom":[{"start":80,"end":100}],
                                "unit":"%",
                                "max":100
                              }';
                            $data = json_decode($data_str, true);
                            return $data;
                        }))
                );
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle(['padding' => '0'])->content(
                    PropertyList::make()
                        ->data(function () {
                            $data_str = '{
                                "attr_names": [
                                    {
                                        "label": "成本",
                                        "prop": "attr_0",
                                        "type": "input"
                                    }, {
                                        "label": "售价",
                                        "prop": "attr_1",
                                        "type": "input"
                                    }, {
                                        "label": "库存",
                                        "prop": "attr_2",
                                        "type": "input"
                                    }, {
                                        "label": "货号",
                                        "prop": "Attr_3",
                                        "type": "select",
                                        "options": [{
                                            "value": "选项1",
                                            "label": "黄金糕"
                                            }, {
                                            "value": "选项2",
                                            "label": "双皮奶"
                                            }, {
                                            "value": "选项3",
                                            "label": "蚵仔煎"
                                            }, {
                                            "value": "选项4",
                                            "label": "龙须面"
                                            }, {
                                            "value": "选项5",
                                            "label": "北京烤鸭"
                                        }]
                                    }
                                  ],
                                  "tableData":[{}]
                              }';
                            $data = json_decode($data_str, true);
                            return $data;
                        })
                ));
            })->row(function (Row $row) {
                $row->gutter(10)->className('mt-10');
                $row->column(12, Card::make()->bodyStyle('padding:5px;')->content(
                    DualAxes::make()->data(function () {
                        return [
                            [
                                ['time' => '2019-03', 'value' => 330, 'count' => 800],
                                ['time' => '2019-04', 'value' => 300, 'count' => 600],
                            ],
                            [
                                ['time' => '2019-03', 'value' => 330, 'count' => 800],
                                ['time' => '2019-04', 'value' => 300, 'count' => 600],
                            ]
                        ];
                    })->config(function () {
                        return [
                            'xField' => 'time',
                            'yField' => ['value', 'count'],
                            'geometryOptions' => [
                                ['geometry' => 'column'],
                                ['geometry' => 'line', 'lineStyle' => ['lineWidth' => 2]]
                            ],
                        ];
                    })
                ));
                $row->column(12, Card::make()->bodyStyle('padding:5px;')->content(
                    Column::make()->data(function () {
                        return [
                            ['month' => '2021-03', 'type' => 'test-1', 'value' => 800],
                            ['month' => '2021-03', 'type' => 'test-2', 'value' => 600],
                        ];
                    })->config(function () {
                        return [
                            'isGroup' => true,
                            'seriesField' => 'type',
                            'xField' => 'month',
                            'yField' => 'value',
                        ];
                    })
                ));
            });
        return $content;
    }

    public function chart_data()
    {
        $data_str = '{
                "width": "",
                "height": "360px",
                "title": {
                  "text": "时间稼动率",
                  "fontSize": 20,
                  "color": "#3E3E3E"
                },
                "subtitle": {
                  "text": "55.62",
                  "fontSize": 30,
                  "color": "#3E3E3E"
                },
                "series": {
                    "color": ["#6573FF", "#2AC7F6"],
                    "data": 55.62
                }
              }';
        $data = json_decode($data_str, true);
        return $data;
    }
    protected function vueData()
    {
        $data['isMsgDialogShow'] = true;
        return $data;
    }
}

