<?php

namespace App\Api\Validates;

class  ClassValidate extends Validate
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
