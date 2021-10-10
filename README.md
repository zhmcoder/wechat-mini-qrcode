
laravel-vue-admin编译
1、进入packages/SmallRuralDog/vue-element-admin,运行npm install
2、再运行npm run development
3、在public目录生成编译后的js相关文件


composer install
composer dump-autoload
php artisan vendor:publish --provider="SmallRuralDog\Admin\AdminServiceProvider"

php artisan migrate --seed
php artisan admin:install

composer require barryvdh/laravel-ide-helper

cd public
ln -s ../storage/app/public/* ./storage/
php artisan storage:link

// 更新静态资源文件（必须）
php artisan vendor:publish --tag=laravel-vue-admin-assets --force

// 强制发布语言包文件（非必须）
php artisan vendor:publish --tag=laravel-vue-admin-lang --force

// 清理视图缓存（非必须）
php artisan view:clear

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
