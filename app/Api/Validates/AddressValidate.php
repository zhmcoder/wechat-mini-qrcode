<?php

namespace App\Api\Validates;

class AddressValidate extends Validate
{
    public function add($request_data)
    {
        $rules = [
            'consignee' => 'required|string',
            'phone' => 'required|string',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'county_id' => 'required|integer',
            'address' => 'required|string',
        ];
        $message = [
            'consignee.required' => '收货人不能为空。',
            'phone.required' => '手机号码不能为空。',
            'province_id.required' => '城市不能为空。',
            'city_id.required' => '省份不能为空。',
            'county_id.required' => '区县不能为空。',
            'address.required' => '详细地址',
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
            'address_id' => 'required|integer',
            'consignee' => 'required|string',
            'phone' => 'required|string',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'county_id' => 'required|integer',
            'address' => 'required|string',
        ];
        $message = [
            'address_id.required' => '收货地址标识不能为空。',
            'consignee.required' => '收货人不能为空。',
            'phone.required' => '手机号码不能为空。',
            'province_id.required' => '城市不能为空。',
            'city_id.required' => '省份不能为空。',
            'county_id.required' => '区县不能为空。',
            'address.required' => '详细地址',
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
