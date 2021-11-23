<?php

namespace App\Api\Validates;

class  OrderCartValidate extends Validate
{
    public function add($request_data)
    {
        $rules = [
            'goods_id' => 'required|integer',
            'num' => 'required|integer',
        ];
        $message = [
            'goods_id.required' => '产品不能为空。',
            'goods_id.integer' => '产品必须为整数',
            'num.required' => '数量不能为空。',
            'num.integer' => '数量必须为整数'
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function list($request_data)
    {
        $rules = [
        ];
        $message = [
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function update($request_data)
    {
        $rules = [
            'goods_id' => 'required|integer',
            'num' => 'required|integer',
        ];
        $message = [
            'goods_id.required' => '产品不能为空。',
            'goods_id.integer' => '产品必须为整数',
            'num.required' => '数量不能为空。',
            'num.integer' => '数量必须为整数'
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function delete($request_data)
    {
        $rules = [

        ];
        $message = [

        ];
        return $this->validate($request_data, $rules, $message);
    }
}
