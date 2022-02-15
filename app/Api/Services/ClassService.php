<?php

namespace App\Api\Services;

use Andruby\DeepGoods\Models\GoodsClass;

/**
 * @method static ClassService instance()
 *
 * Class ClassService
 * @package App\Api\Services
 */
class ClassService
{
    public static function __callStatic($method, $params): ClassService
    {
        return new self();
    }

    public function lists($parent_id)
    {
        $where = [
            'parent_id' => $parent_id,
            'status' => 1
        ];

        $model = GoodsClass::query()->orderByDesc('id')->where($where);
        if ($parent_id) {
            $list = $model->with('children')->get()->toArray();
        } else {
            $list = $model->get()->toArray();
        }

        return collect($list)->map(function ($item) {
            $item['icon'] = http_path($item['icon']);
            return $item;
        });

    }
}
