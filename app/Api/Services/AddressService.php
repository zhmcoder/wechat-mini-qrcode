<?php

namespace App\Api\Services;

use App\Models\Address;
use App\Models\District;

/**
 * @method static AddressService instance()
 *
 * Class AddressService
 * @package App\Api\Services
 */
class AddressService
{
    public static function __callStatic($method, $params): AddressService
    {
        return new self();
    }

    public function list($userInfo, $pageIndex, $pageSize)
    {
        $where = [
            'user_id' => $userInfo['id']
        ];
        $list = Address::query()->where($where)
            ->offset($pageSize * ($pageIndex - 1))->limit($pageSize)
            ->get()->toArray();

        return collect($list)->map(function ($address) {
            $address['province'] = District::query()->find($address['province_id']);
            $address['city'] = District::query()->find($address['city_id']);
            $address['county'] = District::query()->find($address['county_id']);
            return $address;
        });
    }

    public function add($userInfo)
    {
        $consignee = request('consignee');
        $phone = request('phone');
        $province_id = request('province_id');
        $city_id = request('city_id');
        $county_id = request('county_id');
        $address = request('address');
        $type = request('type');
        $is_default = request('is_default');

        $data = [
            'user_id' => $userInfo['id'],
            'consignee' => $consignee,
            'phone' => $phone,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'county_id' => $county_id,
            'address' => $address,
            'type' => $type,
            'is_default' => $is_default,
        ];

        $data = Address::query()->create($data);

        return $data['id'];
    }

    public function update($userInfo)
    {
        $address_id = request('address_id');
        $consignee = request('consignee');
        $phone = request('phone');
        $province_id = request('province_id');
        $city_id = request('city_id');
        $county_id = request('county_id');
        $address = request('address');
        $type = request('type');
        $is_default = request('is_default');

        $data = [
            'user_id' => $userInfo['id'],
            'consignee' => $consignee,
            'phone' => $phone,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'county_id' => $county_id,
            'address' => $address,
            'type' => $type,
            'is_default' => $is_default,
        ];

        $data = Address::query()->updateOrCreate(['id' => $address_id], $data);

        return $data['id'];
    }

    public function delete($userInfo, $address_id)
    {
        $where = [
            'user_id' => $userInfo['id'],
            'id' => $address_id,
        ];

        return Address::query()->where($where)->delete();
    }
}
