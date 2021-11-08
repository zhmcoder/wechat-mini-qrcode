<?php

namespace App\Api\Controllers;

use App\Api\Services\MallHomeService;
use App\Api\Validates\Validate;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request, Validate $validate)
    {
        $app_id = request('app_id', 'deep.mall');
        $os_type = request('os_type', '3');
        $date_time = date('Y-m-d H:i:s');

        $homeConfig = new MallHomeService();
        $data = $homeConfig->home_data($app_id, $os_type, $date_time);
        $this->responseJson(0, 'success', $data);
    }
}

