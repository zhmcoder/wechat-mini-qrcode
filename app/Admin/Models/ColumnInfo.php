<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Admin\Models\HomeColumn
 *
 * @property int $id
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereColumnCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereShelfOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereShelfType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereShowApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereShowMore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereShowName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereShowNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\HomeColumn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColumnInfo extends Model{

    protected $guarded = [];

    protected $table = 'column_info';

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "Y-m-d H:i:s",
        'updated_at' => "Y-m-d H:i:s",
    ];

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
