<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MemberInfo
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 * @property int $id 主键
 * @property string $appid 应用APPID
 * @property string $mch_id 商户号
 * @property string $device_info 设备信息
 * @property string $nonce_str 随机字符串
 * @property string $sign 签名
 * @property string $result_code 业务结果
 * @property string $err_code 错误代码
 * @property string $err_code_des 错误代码描述
 * @property string $openid 用户标识
 * @property string $is_subscribe 是否关注公众账号
 * @property string $trade_type 交易类型
 * @property string $bank_type 付款银行
 * @property int $total_fee 总金额
 * @property string $fee_type 货币种类
 * @property int $cash_fee 现金支付金额
 * @property string $cash_fee_type 现金支付货币类型
 * @property int $coupon_fee 代金券金额
 * @property int $coupon_count 代金券使用数量
 * @property string $transaction_id 微信支付订单号
 * @property string $out_trade_no 商户订单号
 * @property string $attach 商家数据包
 * @property string $time_end 支付完成时间
 * @property string $data 源数据
 * @property \Illuminate\Support\Carbon $create_time 创建时间
 * @property int $user_id 用户id
 * @property string $app_id 应用id
 * @property string|null $channel
 * @property \Illuminate\Support\Carbon|null $update_time
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereAttach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereBankType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereCashFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereCashFeeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereCouponCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereCouponFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereDeviceInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereErrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereErrCodeDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereFeeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereIsSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereMchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereNonceStr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereOutTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereResultCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereTotalFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereTradeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxOrder whereUserId($value)
 */
class WxOrder extends Model
{

    protected $table = 'bmh_wx_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['appid', 'mch_id', 'device_info',
        'nonce_str', 'sign', 'result_code', 'err_code', 'err_code_des', 'openid', 'is_subscribe',
        'trade_type', 'bank_type', 'total_fee', 'fee_type', 'cash_fee', 'cash_fee_type', 'coupon_fee',
        'coupon_count', 'transaction_id', 'out_trade_no', 'attach', 'time_end', 'data', 'user_id', 'app_id',
        'channel'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $visible = ['appid', 'mch_id', 'device_info',
        'nonce_str', 'sign', 'result_code', 'err_code', 'err_code_des', 'openid', 'is_subscribe',
        'trade_type', 'bank_type', 'total_fee', 'fee_type', 'cash_fee', 'cash_fee_type', 'coupon_fee',
        'coupon_count', 'transaction_id', 'out_trade_no', 'attach', 'time_end', 'data', 'user_id', 'app_id',
        'channel'
    ];

    const STATUS = [
        1 => '待支付',
        2 => '支付成功',
        3 => '退款成功',
    ];

    const STATUS_WAITING_PAY = 1;
    const STATUS_PAYED = 2;
    const STATUS_REFUND = 3;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'create_time';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'update_time';

    /**
     * 获取当前时间
     *
     * @return int
     */
    public function freshTimestamp()
    {
        return time();
    }

    /**
     * 避免转换时间戳为时间字符串
     *
     * @param DateTime|int $value
     * @return DateTime|int
     */
    public function fromDateTime($value)
    {
        return $value;
    }

}
