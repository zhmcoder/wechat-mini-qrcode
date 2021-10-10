<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Admin\Models\AppInfo
 *
 * @property int $id
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property string $name 应用名称
 * @property string $app_id 应用appid
 * @property string $os_type os_type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo whereOsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admin\Models\AppInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AppInfo extends Model{

    protected $guarded = [];

    protected $table = 'app_info';

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
