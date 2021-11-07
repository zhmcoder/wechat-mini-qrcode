<?php

namespace App\Api\Controllers;

use Andruby\HomeConfig\Services\HomeConfigService;
use App\Api\Validates\HomeValidate;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    public function index(Request $request, HomeValidate $validate)
    {
        $app_id = request('app_id', 'deep.mall');
        $os_type = request('os_type', '3');
        $date_time = date('Y-m-d H:i:s');

        $data = HomeConfigService::home_data($app_id, $os_type, $date_time);
        $this->responseJson(0, 'success', $data);
    }
}

