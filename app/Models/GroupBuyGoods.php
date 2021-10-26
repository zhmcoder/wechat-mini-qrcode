<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupBuyGoods extends Model
{
    use SoftDeletes;

    public function goodsSku(): BelongsTo
    {
        return $this->belongsTo(GoodsSku::class);
    }
}
