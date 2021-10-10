<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Admin\Models\HomeColumnIds
 *
 * @property int $id
 * @property int $home_column_id
 * @property int $column_id
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds whereHomeColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumnIds whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HomeColumnIds extends Model{

    protected $guarded = [];

    protected $table = 'home_column_ids';

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "Y-m-d H:i:s",
        'updated_at' => "Y-m-d H:i:s",
    ];
}
