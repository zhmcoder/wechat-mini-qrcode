<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Admin\Models\HomeColumn
 *
 * @property int $id
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property string $name 栏目名称
 * @property int $shelf_type 货架类型
 * @property int $show_name 显示名称
 * @property int $show_more 显示更多
 * @property int $shelf_on 是否上架
 * @property int $show_num 显示个数
 * @property string $show_app 展示App
 * @property int $sort 排序
 * @property int $column_count 专栏数量
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
class HomeColumn extends Model{

    protected $guarded = [];

    protected $table = 'home_column';

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
