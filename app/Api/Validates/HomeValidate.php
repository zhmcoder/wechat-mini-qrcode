<?php

namespace App\Api\Validates;

class  HomeValidate extends Validate
{
    public function index($request_data)
    {
        $rules = [

        ];
        $message = [

        ];
        return $this->validate($request_data, $rules, $message);
    }
}
