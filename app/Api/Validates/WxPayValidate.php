<?php

namespace App\Api\Validates;

class  WxPayValidate extends Validate
{

    public function column_info($request_data)
    {
        $rules = [
            'activity_id' => 'required|int',
        ];
        $message = [
            'activity_id.required' => '活动标识不能为空',
            'activity_id.int' => '活动标识值不正确',

        ];
        return $this->validate($request_data, $rules, $message);
    }


    public function weixin($request_data)
    {
        $rules = [
            'cart_ids' => 'required|string',
        ];
        $message = [
            'cart_ids.required' => '购物车标识不能为空',
            'cart_ids.string' => '购物车标识值不正确',
        ];
        return $this->validate($request_data, $rules, $message);
    }

}
