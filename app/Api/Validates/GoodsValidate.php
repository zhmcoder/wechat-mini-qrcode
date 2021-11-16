<?php

namespace App\Api\Validates;

class  GoodsValidate extends Validate
{
    public function list($request_data)
    {
        $rules = [
        ];
        $message = [
        ];

        return $this->validate($request_data, $rules, $message);
    }

    public function detail($request_data)
    {
        $rules = [
            'goods_id' => 'required|integer',
        ];
        $message = [
            'goods_id.required' => '商品不能为空。',
            'goods_id.integer' => '商品必须为整数'
        ];

        return $this->validate($request_data, $rules, $message);
    }
}
