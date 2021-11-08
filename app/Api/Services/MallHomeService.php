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

        collect($goods['images'])->map(function ($image) {
            $image['path'] = http_path($image['path']);
            return $image;
        });

        return $goods;
    }

    protected function shops($table_name, $id)
    {
        $select = ['id', 'name', 'logo'];
        $data = Shop::query()->select($select)->findOrFail($id);

        $data['logo'] = http_path($data['logo']);

        return $data;
    }

}
