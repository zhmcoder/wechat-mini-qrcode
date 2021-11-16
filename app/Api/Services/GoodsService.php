<?php

namespace App\Api\Services;

use App\Models\Goods;

/**
 * @method static GoodsService instance()
 *
 * Class GoodsService
 * @package App\Api\Services
 */
class GoodsService
{
    public static function __callStatic($method, $params): GoodsService
    {
        return new self();
    }

    public function lists($pageIndex, $pageSize)
    {
        $brandId = request('brand_id');
        $keywords = request('keywords');
        $sort_prop = request('sort_prop', 'id');
        $sort_order = request('sort_order', 'desc');

        $where = [
            'on_shelf' => 1,
        ];

        if (!empty($brandId)) {
            $where['brand_id'] = $brandId;
        }
        if (!empty($keywords)) {
            $where[] = ['name', 'like', '%' . $keywords . '%'];
        }

        $fields = ['id', 'brand_id', 'name', 'price', 'line_price'];
        $list = Goods::query()->select($fields)
            ->orderBy($sort_prop, $sort_order)->where($where)
            ->offset($pageSize * ($pageIndex - 1))->limit($pageSize)
            ->get()->toArray();

        return collect($list)->map(function ($goods) {


            $image = collect($goods['images'])->first();
            $goods['image'] = isset($image['path']) ? http_path($image['path']) : '';
            unset($goods['images']);

            return $goods;
        });

    }

    public function detail($goodsId)
    {
        $goods = Goods::query()
            ->with('content')->with('skus')
            ->findOrFail($goodsId);

        collect($goods['images'])->map(function ($image) {
            $image['path'] = http_path($image['path']);
            return $image;
        });

        return $goods;
    }
}
