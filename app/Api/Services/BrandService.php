<?php

namespace App\Api\Services;

use App\Models\Brand;

/**
 * @method static BrandService instance()
 *
 * Class BrandService
 * @package App\Api\Services
 */
class BrandService
{
    public static function __callStatic($method, $params): BrandService
    {
        return new self();
    }

    public function lists()
    {
        $fields = ['id', 'name', 'icon'];
        $list = Brand::query()->select($fields)->orderByDesc('id')->get();

        return collect($list)->map(function ($brand) {
            $brand['icon'] = http_path($brand['icon']);
            return $brand;
        });

    }
}
