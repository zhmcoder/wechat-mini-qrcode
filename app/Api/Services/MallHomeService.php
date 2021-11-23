<?php

namespace App\Api\Services;

use Andruby\HomeConfig\Services\HomeConfigService;
use App\Models\Goods;
use App\Models\GoodsClass;
use App\Models\Shop;

class  MallHomeService extends HomeConfigService
{

    protected function goods($table_info, $config, $config_data)
    {
        $select = ['id', 'name', 'price'];
        $goods = Goods::query()->select($select)->find($config['third_id']);

        if (!empty($goods)) {
            $image = collect($goods['images'])->first();
            $goods['image'] = isset($image['path']) ? http_path($image['path']) : '';
            unset($goods['images']);
        }

        return $goods;
    }

    protected function shops($table_info, $config, $config_data)
    {
        $select = ['id', 'name', 'image'];
        $data = Shop::query()->select($select)->find($config['third_id']);

        if (!empty($data)) {
            $data['image'] = http_path($data['image']);
        }

        return $data;
    }

    protected function goods_classes($table_info, $config, $config_data)
    {
        $select = ['id', 'name', 'icon as image'];
        $data = GoodsClass::query()->select($select)->find($config['third_id']);

        if (!empty($data)) {
            $data['image'] = http_path($data['image']);
        }

        return $data;
    }

}
