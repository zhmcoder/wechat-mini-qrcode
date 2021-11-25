<?php

namespace App\Api\Services;

use App\Models\OrderItem;
use App\Models\Order;

class PayService
{
    public static function out_trade_no($user_id, $product_id, $time)
    {
        return md5($user_id . $product_id . $time);
    }

    public static function create_order($user_id, $out_trade_no, $buy_info, $pre_order_id, $transaction_id, $pay_type = Order::PAY_WEI_XIN, $status = 0, $coupon_id = 0)
    {
        $data['user_id'] = $user_id;
        $data['pay_fee'] = $buy_info['total'];
        $data['status'] = $status;
        $data['pre_order_id'] = $pre_order_id;
        $data['out_trade_no'] = $out_trade_no;
        if ($status == 1) {
            $data['pay_time'] = date('Y-m-d H:i:s', time());
        }
        $data['transaction_id'] = $transaction_id;
        $data['pay_type'] = $pay_type;
        $data['channel'] = request('channel');
        $data['app_id'] = request('appid');
        $data['os_type'] = request('os_type');
        $data['coupon_id'] = $coupon_id; // 优惠券ID
        $data['discount'] = $buy_info['discount'] ?? 0;
        $data['before_discount'] = $buy_info['before_discount'] ?? $buy_info['total'];

        $data = Order::create($data);
        if ($data['id']) {
            return $data;
        } else {
            return null;
        }
    }

    public static function create_order_items($user_id, $order_id, $cart_ids, $pay_status = 0)
    {
        if (!empty($cart_ids) && count($cart_ids) > 0) {
            foreach ($cart_ids as $cart) {
                self::create_order_item($user_id, $cart, $order_id, $pay_status);
            }
        }
    }

    private static function create_order_item($user_id, $cart, $order_id, $pay_status = 0)
    {
        if ($cart) {
            $data['order_id'] = $order_id;
            $data['product_id'] = $cart['goods_id'];
            $data['product_name'] = $cart['title'];
            $data['product_thumb'] = $cart['img_url_compressed'];
            $data['product_type'] = $cart['column_type'];
            $data['price'] = $cart['price']; // 选购价格
            $data['res_type'] = $cart['res_type'];
            $data['amount'] = 1;
            $data['user_id'] = $user_id;
            $data['pay_status'] = $pay_status;

            OrderItem::create($data);
        }
    }

}
