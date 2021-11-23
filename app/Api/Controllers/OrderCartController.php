<?php

namespace App\Api\Controllers;

use App\Api\Services\OrderCartService;
use App\Api\Validates\OrderCartValidate;
use Illuminate\Http\Request;

class OrderCartController extends BaseController
{
    public function add(Request $request, OrderCartValidate $validate)
    {
        $validate_result = $validate->add($request->only(['goods_id', 'num']));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $goods_id = $request->input('goods_id');
            $num = $request->input('num', 1);

            $data = OrderCartService::instance()->add($userInfo, $goods_id, $num);

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function list(Request $request, OrderCartValidate $validate)
    {
        $validate_result = $validate->list($request->only([]));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $pageIndex = $request->input('page_index', 1);
            $pageSize = $request->input('page_size', 20);

            $list = OrderCartService::instance()->lists($userInfo, $pageIndex, $pageSize);

            $data['pageIndex'] = $pageIndex;
            $data['pageSize'] = $pageSize;
            $data['items'] = $list;

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function update(Request $request, OrderCartValidate $validate)
    {
        $validate_result = $validate->update($request->only(['goods_id', 'num']));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $goods_id = $request->input('goods_id');
            $num = $request->input('num', 1);

            $data = OrderCartService::instance()->update($userInfo, $goods_id, $num);

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function delete(Request $request, OrderCartValidate $validate)
    {
        $validate_result = $validate->delete($request->only([]));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $goods_id = $request->input('goods_id');

            $data = OrderCartService::instance()->delete($userInfo, $goods_id);

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }
}

