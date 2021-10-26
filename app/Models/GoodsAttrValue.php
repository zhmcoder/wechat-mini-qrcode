<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttrValue extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;

    public function allValues($goods_attr_id)
    {
        return self::query()->where('goods_attr_id', $goods_attr_id)->orderBy("sort")->get();
    }
}
