<?php

return [
    //后台名称 null不显示
    'name' => env('ADMIN_NAME', 'DeepAdmin'),
    //后台标题
    'title' => env('ADMIN_TITLE', 'DeepAdmin'),
    //登录界面描述
    'loginDesc' => env('LOGIN_DESC', 'DeepAdmin'),
    //logo 地址 null为内置默认 分为黑暗和明亮两种
    'logo_show' => false,
    'logo' => null,
    'logo_mini' => null,
    'logo_light' => null,
    'logo_mini_light' => null,
    //版权
    'copyright' => env('ADMIN_COPYRIGHT', 'DeepAdmin'),
    //默认头像
    'default_avatar' => 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png',
    //登录页面背景
    'login_background_image' => 'https://gw.alipayobjects.com/zos/rmsportal/TVYTbAXWheQpRcWDaDMu.svg',
    //底部菜单
    'footerLinks' => [
        [
            'href' => 'http://www.taixuetv.com',
            'title' => '官网'
        ]
    ],
    'bootstrap' => app_path('Admin/bootstrap.php'),
    'route' => [
        'domain'=>env('ADMIN_DOMAIN', ''),
        'prefix' => env('ADMIN_ROUTE_PREFIX', 'dadmin'),
        'api_prefix' => env('ADMIN_ROUTE_PREFIX', 'admin-api'),
        'namespace' => 'App\\Admin\\Controllers',
        'middleware' => ['web', 'admin'],
    ],
    'directory' => app_path('Admin'),
    'https' => env('ADMIN_HTTPS', false),
    'auth' => [
        'controller' => App\Admin\Controllers\AuthController::class,
        'guard' => 'admin',
        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ],
        ],
        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model' => Andruby\DeepAdmin\Auth\Database\Administrator::class,
            ],
        ],
        // Add "remember me" to login form
        'remember' => true,
        // Redirect to the specified URI when user is not authorized.
        'redirect_to' => 'auth/login',
        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
            '_handle_action_',
        ],
    ],
    'upload' => [
        // Disk in `config/filesystem.php`.
        'disk' => 'public',
        'uniqueName'=>false,
        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file' => 'files',
        ],
        //文件上传类型
        'mimes' => 'jpeg,bmp,png,gif,jpg,mp3,mp4,mpga,apk',
        'image_handle_router' => 'upload.image_handle',
        'file_handle_router' => 'upload.file_handler',
    ],
    'database' => [
        // Database connection for following tables.
        'connection' => '',
        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => Andruby\DeepAdmin\Auth\Database\Administrator::class,
        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => Andruby\DeepAdmin\Auth\Database\Role::class,
        // Permission table and model.
        'permissions_table' => 'admin_permissions',
        'permissions_model' => Andruby\DeepAdmin\Auth\Database\Permission::class,
        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Andruby\DeepAdmin\Auth\Database\Menu::class,
        // Pivot table for table above.
        'operation_log_table' => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table' => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table' => 'admin_role_menu',
        'entities_table' => 'admin_entities',
        'entity_fields_table' => 'admin_entity_fields',
    ],
    //操作日志
    'operation_log' => [
        'enable' => true,
        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs.
         */
        'except' => [
            'admin/auth/logs*',
        ],
    ],
    'check_route_permission' => true,
    'check_menu_roles' => true,
    'map_provider' => 'google',
    'show_version' => true,
    'show_environment' => true,
    'menu_bind_permission' => true,
    'which-composer' => 'composer'

];
