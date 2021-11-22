<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    const CODE_SHOW_MSG = 2001;
    const CODE_ERROR_CODE = -1;
    const CODE_SUCCESS_CODE = 0;

    protected function responseJson($status = -1, $msg = null, $data = null)
    {
        $response["code"] = $status;
        $response["msg"] = $msg;
        if (!empty($data)) {
            $response["data"] = $data;
        }

        $result = " url: " . request()->getUri();
        $result .= " params: " . json_encode(request()->except(['api_sign']));
        $result .= " result: " . json_encode($response);
        result_log_info($result);

        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($response));
    }

    protected function genListData($pageNum, $item_data)
    {
        $data["pageNum"] = $pageNum;
        $data["items"] = $item_data;
        return $data;
    }

    protected function response($status)
    {
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($status));
    }
}

