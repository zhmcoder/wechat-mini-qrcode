<?php

namespace App\Api\Validates;


class  LoginValidate extends Validate
{

    public function login($request_data)
    {
        $rules = [
            'mobile' => 'required|regex:/^1[3456789][0-9]{9}$/',
            'verify_code' => 'required|digits:4',

        ];
        $message = [
            'mobile.required' => '手机号不能为空',
            'mobile.regex' => '手机号格式不正确',
            'verify_code.required' => '验证码不能为空',
            'verify_code.digits' => '验证码位数不正确'
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function mobile($request_data)
    {
        $rules = [
            'mobile' => 'required|regex:/^1[3456789][0-9]{9}$/',
            'img_code' => 'sometimes|required|digits:4',
        ];
        $message = [
            'mobile.required' => '手机号不能为空',
            'mobile.regex' => '手机号格式不正确',
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function weixinLogin($request_data)
    {
        $rules = [
            'openid' => 'required',
            'unionid' => 'required',
            'access_token' => 'required'
        ];
        $message = [
            'openid.required' => 'openid不能为空',
            'unionid.required' => 'required不能为空',
            'access_token.required' => 'access_token不能为空',
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function xcxLogin($request_data)
    {
        $rules = [
            'code' => 'required',
            'loginInfo' => 'sometimes|required',
        ];
        $message = [
            'code.required' => '小程序标识不能为空',
            'loginInfo.required' => '用户信息不能为空',
        ];
        return $this->validate($request_data, $rules, $message);
    }

    public function aliAutoLogin($request_data)
    {
        $rules = [
            'accessToken' => 'required',
        ];
        $message = [
            'accessToken.required' => '参数不能为空',
        ];
        return $this->validate($request_data, $rules, $message);
    }
}
