<?php

namespace App\Api\Controllers;

use App\Api\Services\BuyService;
use App\Api\Services\PayService;
use App\Api\Services\WechatService;
use App\Api\Validates\WxPayValidate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WxOrder;
use App\Models\WxPreorder;
use Illuminate\Http\Request;
use Libs\WxPay\WxPay;
use Yansongda\Pay\Pay;
use Auth;

class WxPayController extends BaseController
{
    private $config;

    public function __construct()
    {
        $appId = request('appid', 'mall');

        $wx_pay = config('mall.wx_pay')[$appId];

        $this->config['appid'] = $wx_pay['appid'];
        $this->config['mch_id'] = $wx_pay['mch_id'];
        $this->config['key'] = $wx_pay['key'];
    }

    public function pay(Request $request, WxPayValidate $validate)
    {
        if ($validate->weixin($request->only(['cart_ids']))) {

            $userInfo = $this->userInfo();
            $user_id = $userInfo['id'];

            $cart_ids = $request->input('cart_ids');

            $attach['app_id'] = $request->input('appid', 'mall');
            $attach['user_id'] = $user_id;

            $buy_info = BuyService::instance()->buy_info($user_id, $cart_ids);
            $out_trade_no = PayService::out_trade_no($user_id, $cart_ids, time());

            try {
                if ($buy_info['total'] == 0) {
                    $order_info = PayService::create_order($user_id, $out_trade_no, $buy_info, -1, 0, Order::PAY_WEI_XIN, Order::STATUS_SUCCESS);
                    PayService::create_order_items($user_id, $order_info['id'], $buy_info, OrderItem::PAY_STATUS_SUCCESS);

                    $this->responseJson(0, '购买成功', ['status' => Order::STATUS_SUCCESS]);
                } else {
                    $pre_order = WechatService::pre_oder_mini($buy_info['title'], $buy_info['total'], $out_trade_no, $attach);
                    if ($pre_order['code'] == 0) {
                        //生成预订单信息
                        WechatService::create_pre_order($user_id, $cart_ids, 1, $pre_order['data'], 1, $out_trade_no);
                        //生成订单信息，未支付
                        $order_info = PayService::create_order($user_id, $out_trade_no, $buy_info, $pre_order['data']['prepayid'], 0, Order::PAY_WEI_XIN, Order::STATUS_WAITING);
                        PayService::create_order_items($user_id, $order_info['id'], $buy_info);

                        $this->responseJson('0', 'success', $pre_order['data']);
                    } else {
                        $this->responseJson('-1', '微信预订单生成错误');
                    }
                }
            } catch (\Exception $e) {
                error_log_info('wxpay error = ' . $e->getMessage());
                $this->responseJson(2002, '购买异常');
            }

        } else {
            $this->responseJson(-1, $validate->message);
        }
    }

    public function notify()
    {
        $pay = Pay::wechat($this->config);

        try {
            $data = $pay->verify();
            $data = $data->toArray();
//            $data = json_decode(request()->getContent(), true);

            $out_trade_no = $data['out_trade_no'];
            $order_update_data['transaction_id'] = $data['transaction_id'];
            $order_update_data['status'] = Order::STATUS_SUCCESS;
            $order_info = Order::where('out_trade_no', $out_trade_no)->first();
            if ($order_info) {
                //修改订单状态
                Order::where('out_trade_no', $out_trade_no)
                    ->update($order_update_data);
                //修改订单明细状态
                OrderItem::where('order_id', $order_info['id'])
                    ->update(['pay_status' => Order::STATUS_SUCCESS]);
                //更新预订单
                WxPreorder::where('out_trade_no', $out_trade_no)->update(['status' => 1]);
                //保存回调信息

                $data['data'] = json_encode($data);
                $attach = json_decode($data['attach'], true);
                $data['app_id'] = $attach['app_id'] ? $attach['app_id'] : 'com.taixue.xunji';
                $data['user_id'] = $attach['user_id'];

                $where['out_trade_no'] = $data['out_trade_no'];

                $preOrderInfo = WxPreorder::where($where)
                    ->select(['xorder_id', 'product_id', 'product_type', 'product_period', 'channel'])
                    ->first();

                $data['channel'] = $preOrderInfo['channel'];
                $wxOrder = WxOrder::updateOrCreate(['out_trade_no' => $out_trade_no], $data);
            } else {
                //输出错误日志
                error_log_info('[wx notify]', ['data' => $data]);
            }

        } catch (\Exception $e) {
            //输出错误日志
            error_log_info('[wx notify error]', ['error' => $e->getMessage()]);
            error_log_info('[wx notify error]', ['wx_data' => request()->getContent()]);
        }

        return $pay->success();// laravel 框架中请直接 `return $pay->success()`
    }
}
