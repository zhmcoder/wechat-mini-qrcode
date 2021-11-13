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
}
