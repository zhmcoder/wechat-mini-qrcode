<?php

namespace App\Api\Controllers;

use App\Api\Services\ClassService;
use App\Api\Validates\ClassValidate;
use Illuminate\Http\Request;

class ClassController extends BaseController
{
    /*
     * 分类列表
     * */
    public function list(Request $request, ClassValidate $validate)
    {
        $validate_result = $validate->list($request->only([]));
        if ($validate_result) {

            $pageIndex = $request->input('page_index', 1);
            $pageSize = $request->input('page_size', 20);
            $parent_id = $request->input('parent_id', 0);

            $list = ClassService::instance()->lists($parent_id, $pageIndex, $pageSize);

            $data['pageIndex'] = $pageIndex;
            $data['pageSize'] = $pageSize;
            $data['items'] = $list;

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }
}

