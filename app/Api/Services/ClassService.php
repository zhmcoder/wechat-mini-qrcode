<?php

namespace App\Api\Services;

use App\Models\GoodsClass;

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

    public function lists($parent_id, $pageIndex, $pageSize)
    {
        $where = [
            'parent_id' => $parent_id,
            'status' => 1
        ];

        $list = GoodsClass::query()->orderByDesc('id')->where($where)
            ->offset($pageSize * ($pageIndex - 1))->limit($pageSize)
            ->get()->toArray();

        return collect($list)->map(function ($item) {
            $item['icon'] = http_path($item['icon']);
            return $item;
        });

    }
}
