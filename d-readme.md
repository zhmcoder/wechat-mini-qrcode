配置镜像源
配置只在当前项目生效  composer config repo.packagist composer https://mirrors.aliyun.com/composer/
取消当前项目  composer config --unset repos.packagist

全局生效  composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
取消全局  composer config -g --unset repos.packagist

npm config set registry https://registry.npm.taobao.org/

laravel-vue-admin编译
1、进入packages/SmallRuralDog/vue-element-admin,运行 npm install
2、再运行 npm run development或npm run production
3、在public目录生成编译后的js相关文件

1、进入packages/Andruby/deep-admin,运行 npm install
2、再运行 npm run development或npm run production
3、在public目录生成编译后的js相关文件


项目根目录
composer install
composer update
composer dump-autoload
php artisan vendor:publish --provider="SmallRuralDog\Admin\AdminServiceProvider"
php artisan vendor:publish --provider="Andruby\DeepAdmin\AdminServiceProvider"
php artisan key:generate

生成密钥
php artisan key:generate

生成表结构数据
php artisan migrate --seed
php artisan admin:install

依赖包 修改表结构、表字段
composer require barryvdh/laravel-ide-helper

cd public
ln -s ../storage/app/public/* ./storage/
php artisan storage:link

强制发布资源
php artisan vendor:publish --tag=laravel-vue-admin-assets --force
php artisan vendor:publish --tag=deep-admin-assets --force

字段设置通用：
1、字段名、字段标题
2、字段数据库类型
根据上面两自动生成表单名称与表单类型，并生成简单的校验规则，如数字
3、展示：行内展示
4、显示：不显示、编辑显示、创建显示、始终显示
5、是否为空、默认值、必填
6、校验规：唯一、正则、长度、函数
7、字段说明
8、表单内提示

列表展示
1、可设置哪些字段在列表展示、字段对应的点击连接或动作，展示字段可调整顺序
2、字段是否排序功能
3、操作列表可定义，默认删除、编辑

表单展示
1、可设置哪些字段展示，展示字段可调整顺序
2、行显示或行显示


Vue修改记录
1、20200614 添加删除、编辑 带参数问题, resources/js/components/widgets/Actions/DeleteAction.vue, resources/js/components/widgets/Actions/EditAction.vue
2、20200618 修改form显示问题, 去掉创建 resources/js/components/form/Form.vue
3、20200620 修改Transfer, 穿梭框默认宽度调整 resources/js/components/widgets/Form/Transfer.vue
4、20200620 修改ActionButton，增加request请求中如果添加了res.data.action.emit，但grid触发事件
5、20200620 添加拖动排序，拖动后修改sort字段
6、20200702 解决菜单带参不能选中问题 resources/js/components/Root.vue
7、20200704 修改menu菜单数据 src/AuthDatabase/AdminTablesSeeder.php


规划1111
1、.env中的配置部分在后实现可以视化修改
2、grid列表中列可以添加链接操作，在后台中的模型操作中可配置
3、grid操作中添加操作选项和跳转链接，在后台中的模型操作中可配置
4、模型自动添加到菜单？？待定

问题：
1、CSRF token mismatch. 要跳转登录


集成功能：
1、支付（微信、支付宝、Palpay等）
2、三方登录,微信、手机号、qq等
3、基本的用户体系，并支持用户信息扩展
4、基础接口，接口签名、异常等
5、


1、下拉框与下拉框关联
需要设置两个参数，一个是是否关联下拉框->isRelatedSelect(true)，二是关联下拉框的id，
通过relatedSelectRef('select_related')来设置关联操作的下拉框
Select::make()->filterable()
    ->isRelatedSelect(true) //添加方法
    ->relatedSelectRef('select_related')  //添加方法
    ->ref('test_select')
    ->options($this->_selectOptionTable($val))

关联下拉框设置，注意关联下拉框需要是远程搜索通过接口动态变化获取数据，请求接口中参数名称为上面设置的关联id
select_related，接口通过select_related参数处理并返回关联下拉框的关联数据

Select::make()->options($options)
    ->extUrlParams(['val' => $val])
    ->remote($this->remoteUrl) //必需添加
    ->ref('select_related')
    ->clearable()->placeholder('请输入查询')
    
  remoteUrl 远程接口处理与下拉选中相关的逻辑。
  
2、下拉框与关联设置form中输入框值内容
下拉框要设置是关联输入值字段的key，relatedComponents方法是一个数据，示例如下
Select::make()->options($options)
    ->extUrlParams(['val' => $val])
    ->remote($this->remoteUrl)
    ->ref('select_related')
    ->relatedComponents(['tes_un_sd','tes_un'])  //关联修改的字段
    ->clearable()->placeholder('请输入查询')

options返回的每一个项数据中要有关联字段对不的值，示例：
$data = [
    'data' => [
        'total' => 1,
        'data' => [['value' => 1, 'type' => "defalut",
            'label' => 'news one ' . $select_related,
            'tes_un_sd' => 'news one ' . $select_related,
            'tes_un'=>'1 news one ' . $select_related
        ],
            ['value' => 2, 'type' => "defalut",
                'label' => 'news two ' . $select_related,
                'tes_un_sd' => 'news two ' . $select_related,
                'tes_un'=>'2 news one ' . $select_related
            ]
        ],
    ],
    ];

ToolButton点击携带搜索Form数据需要设置isFilterFormData(true)，示例
$toolbars->addRight(
            Grid\Tools\ToolButton::make("导出")
                ->icon("el-icon-plus")
                ->requestMethod('post')
                ->params(['id'=>'11','name1'=>'name'])
                ->isFilterFormData(true)
                ->handler(Grid\Tools\ToolButton::HANDLER_REQUEST)
                ->uri(route('home.column.export'))
        );
