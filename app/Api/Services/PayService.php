<?php


namespace App\Api\Services;


class PayService
{
    public static function out_trade_no($user_id, $product_id, $time)
    {
        return md5($user_id . $product_id . $time);
    }

    public static function pay_qrcode()
    {
        $pre_order = WechatService::pre_oder_scan('测试', 1,
            md5(time() . 'sdf'), ['user_id' => 1]);
        echo json_encode($pre_order);

        exit();
    }

    public static function transfer($openid, $trade_no, $title, $price)
    {
//        $pre_order = WechatService::transfer('乐享优惠霸王餐活动返现', 1,
//            md5(time() . 'sdf'), ['user_id' => 1]);
//        echo json_encode($pre_order);
//
//        exit();
    }
}
