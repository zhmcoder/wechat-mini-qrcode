<?php

namespace App\Api\Controllers;

use App\Api\Services\ActivityService;
use App\Api\Services\PayService;
use App\Api\Services\WechatService;
use App\Api\Validates\WxPayValidate;
use App\Models\Activity;
use App\Models\Business;
use App\Models\JoinInfo;
use App\Models\WxOrder;
use App\Models\WxPreorder;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay;
use Auth;
use function AlibabaCloud\Client\json;

class WxPayController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->config['appid'] = config('mall.' . request('appid') . '.appid');
        $this->config['mch_id'] = config('mall.' . request('appid') . '.mch_id');
        $this->config['key'] = config('mall.' . request('appid') . '.key');

        debug_log_info(json_encode($this->config));
    }

    public function pay(Request $request, WxPayValidate $validate)
    {
        if ($validate->weixin($request->only(['activity_id']))) {

            $user_id = Auth::id();

            $activity_id = $request->input('activity_id');

            $activityInfo = Activity::find($activity_id);
            $businessInfo = Business::find($activityInfo['business_id']);

            $attach['user_id'] = $user_id;
            $attach['appid'] = $request->input('appid');

            $buy_info['total'] = config('lehui.order_pay'); // 支付金额

            $out_trade_no = PayService::out_trade_no($user_id, $activity_id, time());

            // 生成订单信息，未支付
            $data = ActivityService::instance()->join($activity_id, $out_trade_no, $buy_info['total']);
            if ($data['status'] == 200) {
                $preOrderInfo = WxPreorder::query()->where(['out_trade_no' => $data['out_trade_no']])->select(['data'])->first();
                if (!empty($preOrderInfo)) {
                    $pre_order['data'] = json_decode($preOrderInfo['data'], true);
                } else {
                    $pre_order = WechatService::pre_oder_mini($businessInfo['name'], $buy_info['total'], $data['out_trade_no'], $attach);
                    if ($pre_order['code'] == 0) {
                        // 生成预订单信息
                        WechatService::create_pre_order_mini($user_id, $activity_id, null, 1, $buy_info['total'], $pre_order['data'], $data['out_trade_no']);
                    } else {
                        $this->responseJson('-1', '微信预订单生成错误', $pre_order);
                    }
                }

                $pre_order['data']['join_id'] = $data['join_id']; // 返回报名ID

                $this->responseJson('0', 'success', $pre_order['data']);

            } else {
                $this->responseJson('-1', $data['msg']);
            }
        } else {
            $this->responseJson(-1, $validate->message);
        }
    }

    public function notify(Request $request)
    {

        $pay = Pay::wechat($this->config);

        try {
            $data = $pay->verify();
            debug_log_info("verify data = " . json_encode($data));
            $attach = json_decode($data['attach'], true);
            $out_trade_no = $data['out_trade_no'];

            // $order_update_data['transaction_id'] = isset($data['transaction_id']) ? $data['transaction_id'] : '';
            $order_update_data['order_status'] = JoinInfo::STATUS_PAYED;

            $joinInfo = JoinInfo::query()->where('out_trade_no', $out_trade_no)->first();
            if ($joinInfo) {
                //修改订单状态
                JoinInfo::query()->where('out_trade_no', $out_trade_no)->update($order_update_data);

                // todo 更新活动数量
                // Activity::query()->where('id', $joinInfo['activity_id'])->increment('join_num', 1);

                //更新预订单
                WxPreorder::query()->where('out_trade_no', $out_trade_no)->update(['status' => JoinInfo::STATUS_PAYED]);
                //保存回调信息

                $data['attach'] = json_encode($attach);
                $data['data'] = json_encode($data);
                $data['app_id'] = $attach['appid'];
                $data['user_id'] = $attach['user_id'];

                $where['out_trade_no'] = $data['out_trade_no'];

                $preOrderInfo = WxPreorder::query()->where($where)
                    ->select(['product_id', 'product_type', 'product_period', 'channel', 'pay_fee'])
                    ->first();

                $data['channel'] = $preOrderInfo['channel'];
                debug_log_info('out_trade_no=' . $out_trade_no);
                debug_log_info('wx order data = ' . json_encode($data));

                $updateData = [
                    'appid' => $data['appid'],
                    'attach' => $data['attach'],
                    'bank_type' => $data['bank_type'],
                    'cash_fee' => $data['cash_fee'],
                    'fee_type' => $data['fee_type'],
                    'is_subscribe' => $data['is_subscribe'],
                    'mch_id' => $data['mch_id'],
                    'nonce_str' => $data['nonce_str'],
                    'openid' => $data['openid'],
                    'out_trade_no' => $data['out_trade_no'],
                    'result_code' => $data['result_code'],
                    'return_code' => $data['return_code'],
                    'sign' => $data['sign'],
                    'time_end' => $data['time_end'],
                    'total_fee' => $data['total_fee'],
                    'trade_type' => $data['trade_type'],
                    'transaction_id' => $data['transaction_id'],
                    'data' => $data['data'],
                    'user_id' => $data['user_id'],
                ];

                $wxOrder = WxOrder::query()->updateOrCreate(['out_trade_no' => $out_trade_no], $updateData);

            } else {
                //输出错误日志
                error_log_info('[wx notify]', ['data' => $data]);
            }

        } catch (\Exception $e) {
            //输出错误日志
            debug_log_info('[wx notify error]', ['error' => $e->getMessage()]);
            debug_log_info('[wx notify error]', ['wx_data' => request()->getContent()]);
        }

        return $pay->success();
    }
}
