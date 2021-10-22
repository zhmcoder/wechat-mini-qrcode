<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSkuStock extends Model
{
    use SoftDeletes;

    protected $table = "goods_sku_stock";

    public $timestamps = false;

    protected $guarded = [];
}
