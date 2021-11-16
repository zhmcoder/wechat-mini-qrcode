<?php

namespace App\Api\Controllers;

use App\Api\Services\GoodsService;
use App\Api\Validates\GoodsValidate;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{

    public function list(Request $request, GoodsValidate $validate)
    {
        $validate_result = $validate->list($request->only([]));
        if ($validate_result) {

            $pageIndex = $request->input('page_index', 1);
            $pageSize = $request->input('page_size', 20);

            $list = GoodsService::instance()->lists($pageIndex, $pageSize);

            $data['pageIndex'] = $pageIndex;
            $data['pageSize'] = $pageSize;
            $data['items'] = $list;

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function detail(Request $request, GoodsValidate $validate)
    {
        $validate_result = $validate->detail($request->only(['goods_id']));
        if ($validate_result) {

            $goodsId = $request->input('goods_id');
            $list = GoodsService::instance()->detail($goodsId);

            $data['items'] = $list;

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }
}

