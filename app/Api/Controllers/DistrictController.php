<?php

namespace App\Api\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends BaseController
{

    public function list(Request $request)
    {
        $level = $request->input('level', 1);
        $parent_id = $request->input('parent_id', 0);

        $where = [
            'level' => $level,
            'parent_id' => $parent_id,
        ];
        $list = District::query()->where($where)->get(['id', 'name'])->toArray();

        $data['items'] = $list;

        $this->responseJson($this::CODE_SUCCESS_CODE, 'success', $data);
    }

}

