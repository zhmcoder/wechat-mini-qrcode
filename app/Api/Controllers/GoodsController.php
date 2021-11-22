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

            $this->responseJson(self::CODE_SUCCESS_CODE, 'success', $data);
        } else {
            $this->responseJson(self::CODE_ERROR_CODE, $validate->message);
        }
    }

    public function detail(Request $request, GoodsValidate $validate)
    {
        $validate_result = $validate->detail($request->only(['goods_id']));
        if ($validate_result) {

            $goodsId = $request->input('goods_id');
            $goods = GoodsService::instance()->detail($goodsId);
            if (empty($goods)) {
                $this->responseJson(self::CODE_SHOW_MSG, '商品已下架');
            }

            $this->responseJson(self::CODE_SUCCESS_CODE, 'success', $goods);
        } else {
            $this->responseJson(self::CODE_ERROR_CODE, $validate->message);
        }
    }
}

