<?php

namespace App\Api\Controllers;

use App\Api\Services\AddressService;
use App\Api\Validates\AddressValidate;
use Illuminate\Http\Request;

class AddressController extends BaseController
{

    public function list(Request $request)
    {
        $userInfo = $this->userInfo();

        $pageIndex = $request->input('page_index', 1);
        $pageSize = $request->input('page_size', 20);

        $list = AddressService::instance()->list($userInfo, $pageIndex, $pageSize);

        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;
        $data['items'] = $list;

        $this->responseJson($this::CODE_SUCCESS_CODE, 'success', $data);
    }

    public function add(Request $request, AddressValidate $validate)
    {
        $validate_result = $validate->add($request->only(['consignee', 'phone', 'province_id', 'city_id', 'county_id', 'address']));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $data = AddressService::instance()->add($userInfo);

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function update(Request $request, AddressValidate $validate)
    {
        $validate_result = $validate->update($request->only(['address_id', 'consignee', 'phone', 'province_id', 'city_id', 'county_id', 'address']));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $data = AddressService::instance()->update($userInfo);

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function delete(Request $request, AddressValidate $validate)
    {
        $validate_result = $validate->delete($request->only(['address_id']));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $address_id = $request->input('address_id');

            $data = AddressService::instance()->delete($userInfo, $address_id);

            $this->responseJson('0', 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }

    public function info(Request $request, AddressValidate $validate)
    {
        $validate_result = $validate->info($request->only(['address_id']));
        if ($validate_result) {
            $userInfo = $this->userInfo();

            $address_id = $request->input('address_id');

            $data = AddressService::instance()->info($userInfo, $address_id);
            if (!empty($data)) {
                $this->responseJson('0', 'success', $data);
            } else {
                $this->responseJson('-1', '收货地址信息不存在');
            }
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }
}

