<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeColumn extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "Y-m-d H:i:s",
        'updated_at' => "Y-m-d H:i:s",
    ];

    const SHELF_TYPE = [
        1 => '轮播图',
        2 => '金刚圈',
        3 => '商品',
    ];

    const SHELF_TYPE_TABLE = [
        1 => 'home_configs',
        2 => 'home_configs',
        3 => 'goods',
    ];
}
