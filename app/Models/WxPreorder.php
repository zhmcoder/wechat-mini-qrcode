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
 * @property string $return_code 返回状态码
 * @property string $return_msg 返回信息
 * @property string $appid 微信支付APPID
 * @property string $mch_id 商户号
 * @property string $device_info 设备号
 * @property string $nonce_str 随机字符串
 * @property string $sign 签名
 * @property string $result_code 业务结果
 * @property string $err_code 错误代码
 * @property string $err_code_des 错误代码描述
 * @property string $data 预订单数据
 * @property \Illuminate\Support\Carbon $create_time 创建时间
 * @property string $trade_type 交易类型
 * @property string $prepay_id 预支付交易会话标识
 * @property string $out_trade_no 商户订单号
 * @property string $product_id 商品id
 * @property int|null $product_period 购买商品期限
 * @property int $status 状态
 * @property int $user_id 用户id
 * @property int $pay_fee 支付金额
 * @property string $app_id 应用id
 * @property string $product_type 商品类型
 * @property string|null $channel
 * @property \Illuminate\Support\Carbon|null $update_time
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereDeviceInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereErrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereErrCodeDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereMchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereNonceStr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereOutTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder wherePayFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder wherePrepayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereProductPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereResultCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereReturnCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereReturnMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereTradeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereXorderId($value)
 * @property string|null $product_name 商品名称
 * @method static \Illuminate\Database\Eloquent\Builder|WxPreorder whereProductName($value)
 */
class WxPreorder extends Model
{

    protected $table = 'bmh_wx_preorder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['return_code', 'return_msg', 'appid', 'mch_id', 'device_info',
        'nonce_str', 'sign', 'result_code', 'err_code', 'err_code_des', 'data', 'trade_type',
        'prepay_id', 'out_trade_no', 'product_id', 'product_period', 'status', 'user_id',
        'pay_fee', 'product_type', 'channel'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $visible = [
        'id', 'return_code', 'return_msg', 'appid', 'mch_id', 'device_info',
        'nonce_str', 'sign', 'result_code', 'err_code', 'err_code_des', 'data', 'trade_type',
        'prepay_id', 'out_trade_no', 'product_id', 'product_period', 'status', 'user_id',
        'pay_fee', 'product_type', 'channel'
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
