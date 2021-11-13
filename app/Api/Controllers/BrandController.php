<?php

namespace App\Api\Controllers;

use App\Api\Services\BrandService;
use App\Api\Validates\BrandValidate;
use Illuminate\Http\Request;

class BrandController extends BaseController
{

    public function list(Request $request, BrandValidate $validate)
    {
        $list = BrandService::instance()->lists();

        $data['items'] = $list;

        $this->responseJson('0', 'success', $data);
    }
}

