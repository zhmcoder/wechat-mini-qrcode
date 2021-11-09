<?php

namespace App\Api\Services;

use Andruby\HomeConfig\Services\HomeConfigService;
use App\Models\Goods;
use App\Models\Shop;

class  MallHomeService extends HomeConfigService
{

    protected function goods($table_name, $id)
    {
        $select = ['id', 'name', 'price'];
        $goods = Goods::query()->select($select)->findOrFail($id);

        $image = collect($goods['images'])->first();
        $goods['image'] = isset($image['path']) ? http_path($image['path']) : '';
        unset($goods['images']);

        return $goods;
    }

    protected function shops($table_name, $id)
    {
        $select = ['id', 'name', 'image'];
        $data = Shop::query()->select($select)->findOrFail($id);

        $data['image'] = http_path($data['image']);

        return $data;
    }

}
