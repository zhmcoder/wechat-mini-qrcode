<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    const PAY_WEI_XIN = 1; // 微信购买类型

    const PAY_STATUS_WAIT = 0; // 待支付
    const PAY_STATUS_SUCCESS = 1; // 支付成功
    const PAY_STATUS_FAIL = 2; // 支付失败

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
