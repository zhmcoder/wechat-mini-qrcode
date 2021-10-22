<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $fillable = [
        'username', 'password', 'name', 'avatar', 'remember_token',
        'openid', 'mobile', 'amount', 'sex', 'country', 'province',
        'city', 'language', 'frozen_amount', 'alipay_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'password', 'remember_token'
    ];
}
