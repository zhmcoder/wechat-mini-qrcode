<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    const PAY_WEI_XIN = 1; // 微信购买类型

    const PAY_TYPE = [
        1 => '微信',
        2 => '币',
        3 => '小鹅通',
        4 => 'PayPal',
    ];

    const STATUS_WAITING = 0; // 等待支付
    const STATUS_SUCCESS = 1; // 支付成功
    const STATUS_FAILED = 2; // 支付失败
    const STATUS_CANCEL = 3; // 支付取消

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
