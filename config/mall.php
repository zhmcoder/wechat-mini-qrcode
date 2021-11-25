<?php

return array(
    //用户
    'wx21e205bfb5ccdefd' => [
        'appid' => 'wx21e205bfb5ccdefd',
        'miniapp_id' => 'wx21e205bfb5ccdefd',
        'appsecret' => '355b096d5dabbc6bf3b996710b5202bc',
        'mch_id' => '1519655851',
        'key' => 'LXYH87snvmbj83ksd1522q3sk38293rs',
        'cert_client' => storage_path('cert/1519655851/apiclient_cert.pem'),
        'cert_key' => storage_path('cert/1519655851/apiclient_key.pem'),
        'app_name' => '优享乐惠',
        'xcx_tmpl_ids' => [
            'pay_order' => [
                'image_upload' => [
                    'tmpl_id' => '5D74vOyOJjtknhgWHiPjwtDz0ufFWHCUkoRXvYGVDjA',
                    'path' => 'pages/order/index?tab=order&index=todo'
                ],
                'will_expire' => [
                    'tmpl_id' => 'MPBxzkRUl6ozCGwR5ArE3SIHqdZpbworFGRZ8GrDaYE',
                    'path' => 'pages/order/index?tab=order&index=todo'
                ],
                'review_result' => [
                    'tmpl_id' => '88Ps8ojv33IsnRtGQpzlanoBXzjk_yPgUajFtjeAPc0',
                    'audit_path' => 'pages/order/index?tab=order&index=reviewed',
                    'reject_path' => 'pages/order/index?tab=order&index=todo'
                ]
            ],
            'comment_submit' => [
                'review_result' => [
                    'tmpl_id' => '88Ps8ojv33IsnRtGQpzlanoBXzjk_yPgUajFtjeAPc0',
                    'audit_path' => 'pages/order/index?tab=order&index=reviewed',
                    'reject_path' => 'pages/order/index?tab=order&index=todo'
                ],
                'order_finish' => [
                    'tmpl_id' => 'mLnl63ifhw7D88t8UGLYdFhfgvMqB4Twc3qbeAmx_ss',
                    'path' => 'pages/order/index?tab=order&index=reviewed'
                ]
            ],
        ],
    ],
    'wx_pay' => [
        'log' => [ // optional
            'file' => '../storage/logs/wechat.log',
            'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0
        ],
        'mode' => 'normal', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ],

    // 用户小程序appid
    'user_xcx_appid' => env('USER_XCX_APPID', 'wx1f2056da559100fb'),

    'xcx_message' => [
        'log' => [
            'level' => 'debug',
            'type' => 'daily',
            'file' => storage_path('logs/xcx_message_log.log'),
            'max_file' => 30,
        ]
    ],

    'aes_key' => '8UJan%^&1^G4KIUJ',

    'head_default_url' => '/Uploads/Head/head_defualt.jpg',

    'max_ranges' => 1000000, // 最大距离（km）

    'comment_max_time' => 16, // 评论最大时间（小时）

    'order_pay' => 1, // 支付金额（分）

    'order_auto_cancel_time' => env('ORDER_AUTO_CANCEL_TIME', 5 * 60), //抢订单后自动取消时间，单位秒

    'order_expired_message_time' => env('ORDER_EXPIRED_MESSAGE_TIME', 12), // 推送过期消息（小时）
    'order_comment_message_time' => env('ORDER_COMMENT_MESSAGE_TIME', 1), // 订单支付凭证消息推送（小时）

    'cash_out_alipay' => 1,
    'cash_out_wechat' => 2,
    'cash_out_type' => env('CASH_OUT_TYPE', 2), // 1 支付宝 2 微信

    'cache_business_time' => env('CACHE_BUSINESS_TIME', 60 * 10), // 缓存商家时间 分钟

    'cancel_refund_switch' => env('CANCEL_REFUND_SWITCH', 0), // 取消退款开关

    'activity_config_count' => env('ACTIVITY_CONFIG_COUNT', 3), // 活动配置数量
);
