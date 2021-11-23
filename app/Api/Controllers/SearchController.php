<?php

namespace App\Api\Controllers;

use App\Api\Services\BrandService;
use App\Api\Validates\BrandValidate;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function list(Request $request, BrandValidate $validate)
    {
        $brand = BrandService::instance()->lists();

        $data[] = [
            'name' => '品牌',
            'key' => 'brand_id',
            'is_active' => false,
            'list' => $brand,
        ];

        $this->responseJson('0', 'success', $data);
    }
}

