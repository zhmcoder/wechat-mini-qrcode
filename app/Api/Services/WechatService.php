<?php

namespace App\Api\Services;

use App\Models\WxPreorder;
use Auth;
use Yansongda\Pay\Pay;
use function AlibabaCloud\Client\json;

class WechatService extends PayService
{
    public static function pre_oder_scan($title, $price, $out_trade_no, $attach)
    {
        $order = [
            'out_trade_no' => $out_trade_no,
            'total_fee' => $price, // **单位：分**
            'body' => $title,
            'notify_url' => route('wx_pay.notify'),
            'nonce_str' => self::get_nonce_string()
        ];

        return null;
    }

    /**
     * 生成小程序预订单信息
     *
     * @param $title
     * @param $price
     * @param $out_trade_no
     * @param $attach
     * @param $notify_url
     * @param $openid
     * @return mixed
     */
    public static function pre_oder_mini($title, $price, $out_trade_no, $attach, $notify_url = '', $openid = '')
    {
        $order = [
            'out_trade_no' => $out_trade_no,
            'total_fee' => $price, // **单位：分**
            'body' => $title,
            'notify_url' => !empty($notify_url) ? $notify_url : route('wx_pay.notify', ['appid' => request('appid')]),
            'nonce_str' => self::get_nonce_string(),
        ];

        $order['openid'] = !empty($openid) ? $openid : Auth::user()->username;

        if (!empty($attach)) {
            $order['attach'] = json_encode($attach);
        }
        try {
            $config = config('lehui.wx_pay');
            $config = array_merge($config, config('lehui.' . request('appid')));

            $pay = Pay::wechat($config)->miniapp($order);
            $status['code'] = 0;
            $status['msg'] = 'success';
            $status['data'] = $pay;

        } catch (\Exception $ex) {
            $status['code'] = -1;
            $status['msg'] = $ex->getMessage();
        }

        return $status;
    }
//        {
//            "return_code": "SUCCESS",
//    "return_msg": [],
//    "mch_appid": "wx21e205bfb5ccdefd",
//    "mchid": "1519655851",
//    "nonce_str": "sd0umcd1HGnyy15W",
//    "result_code": "SUCCESS",
//    "partner_trade_no": "0679e8d12e9fe65eb4dfd3717551cf56",
//    "payment_no": "10101032559102102167123606883834",
//    "payment_time": "2021-02-16 19:52:30"
//}

    /**
     * @param $openid 小程序openid
     * @param $title 标题
     * @param $trade_no 订单号
     * @param $price 单位分
     * @return bool|void
     */
    public static function transfer($openid, $trade_no, $title, $price)
    {
        if (env('WX_PAY_DEV', false)) {
            $price = 100;
        }
        $order = [
            'partner_trade_no' => $trade_no,
            'openid' => $openid,
            'amount' => $price, // **单位：分**
            'desc' => $title,
            'check_name' => 'NO_CHECK',
        ];

        $config = config('lehui.wx_pay');
        $order['type'] = 'miniapp';
        $config = array_merge($config, config('lehui.' . request('appid')));

        $status = Pay::wechat($config)->transfer($order);

        if ($status['return_code'] == 'SUCCESS' && $status['result_code'] == 'SUCCESS') {
            return true;
        } else {
            error_log_info('cash error openid = ' . ' msg = ' . json_encode($status));
            return false;
        }

    }

    public static function create_pre_order($user_id, $product_id, $product_type, $pay_fee, $pre_order, $out_trade_no)
    {

        $result['return_code'] = 'SUCCESS';
        $result['return_msg'] = 'OK';

        $result['data'] = json_encode($pre_order);
        $result['product_id'] = $product_id;
        $result['out_trade_no'] = $out_trade_no;
        $result['product_period'] = 0;
        $result['product_type'] = $product_type;
        $result['user_id'] = $user_id;
        $result['create_time'] = time();
        $result['pay_fee'] = $pay_fee;

        $result['app_id'] = request('appid');
        $result['channel'] = request('channel');


        $result['appid'] = $pre_order['appid'];
        $result['mch_id'] = $pre_order['partnerid'];
        $result['prepay_id'] = $pre_order['prepayid'];
        $result['nonce_str'] = $pre_order['noncestr'];
        $result['package'] = $pre_order['package'];
        $result['sign'] = $pre_order['sign'];

        $order_info = WxPreorder::create($result);

        if ($order_info) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 支付预订单
     *
     * @param $user_id
     * @param $product_id
     * @param $product_type
     * @param $pay_fee
     * @param $pre_order
     * @param $out_trade_no
     * @return bool
     */
    public static function create_pre_order_mini($user_id, $product_id, $product_name,
                                                 $product_type, $pay_fee, $pre_order, $out_trade_no)
    {

        $result['return_code'] = 'SUCCESS';
        $result['return_msg'] = 'OK';

        $result['data'] = json_encode($pre_order);
        $result['product_id'] = $product_id;
        $result['product_name'] = $product_name;
        $result['out_trade_no'] = $out_trade_no;
        $result['product_period'] = 0;
        $result['product_type'] = $product_type;
        $result['user_id'] = $user_id;
        $result['create_time'] = time();
        $result['pay_fee'] = $pay_fee;

        $result['app_id'] = request('appid');
        $result['channel'] = request('channel');

        $result['appid'] = $pre_order['appId'];
        $result['mch_id'] = config('lehui.' . request('appid') . '.mch_id');
        $result['prepay_id'] = $pre_order['package'];
        $result['nonce_str'] = $pre_order['nonceStr'];
        $result['package'] = $pre_order['package'];
        $result['sign'] = $pre_order['paySign'];

        $order_info = WxPreorder::query()->updateOrCreate(['out_trade_no' => $out_trade_no], $result);

        if ($order_info) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_nonce_string()
    {
        return str_shuffle('pysnow530pysnow530pysnow530');
    }

    // 订单退款(小程序)
    public static function refund($order): array
    {
        $order = [
            'out_trade_no' => $order['out_trade_no'],
            'out_refund_no' => PayService::out_trade_no($order['user_id'], $order['activity_id'], time()),
            'total_fee' => $order['pay_fee'],
            'refund_fee' => $order['pay_fee'],
            'refund_desc' => '订单取消',
            'nonce_str' => self::get_nonce_string(),
            'type' => 'miniapp',
        ];

        try {
            $config = config('lehui.wx_pay');
            $config = array_merge($config, config('lehui.' . request('appid')));

            $pay = Pay::wechat($config)->refund($order);

            $result['code'] = 0;
            $result['msg'] = 'success';
            $result['data'] = $pay;

        } catch (\Exception $ex) {
            $result['code'] = -1;
            $result['msg'] = $ex->getMessage();

            error_log_info('[wx refund]', ['data' => $result]);
        }

        return $result;
    }
}
