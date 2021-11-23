<?php

namespace App\Api\Services;

use App\Models\OrderCart;

/**
 * @method static OrderCartService instance()
 *
 * Class OrderCartService
 * @package App\Api\Services
 */
class OrderCartService
{
    public static function __callStatic($method, $params): OrderCartService
    {
        return new self();
    }

    public function add($userInfo, $goods_id, $num)
    {
        $where = [
            'user_id' => $userInfo['id'],
            'goods_id' => $goods_id,
        ];
        $data = [
            'user_id' => $userInfo['id'],
            'goods_id' => $goods_id,
            'num' => $num,
        ];

        $cart = OrderCart::query()->where($where)->first();
        if (!empty($cart)) {
            $data['num'] += $cart['num'];
            OrderCart::query()->where($where)->update($data);
            $cart_id = $cart['id'];
        } else {
            $data = OrderCart::query()->create($data);
            $cart_id = $data['id'];
        }

        return $cart_id;
    }

    public function lists($userInfo, $pageIndex, $pageSize)
    {
        $where = ['user_id' => $userInfo['id']];
        $data = OrderCart::query()->orderByDesc('id')->where($where)
            ->offset($pageSize * ($pageIndex - 1))->limit($pageSize)
            ->get()->toArray();

        return collect($data)->map(function ($item, $index) {
            $item['goods']['images'] = collect($item['goods']['images'])->first();
            $item['goods']['images'] = isset($item['goods']['images']['path']) ? http_path($item['goods']['images']['path']) : '';
            return $item;
        });
    }

    public function update($userInfo, $goods_id, $num)
    {
        $where = [
            'user_id' => $userInfo['id'],
            'goods_id' => $goods_id,
        ];
        $data = [
            'num' => $num,
        ];

        $cart = OrderCart::query()->where($where)->first();
        if (!empty($cart)) {
            OrderCart::query()->where($where)->update($data);
            $cart_id = $cart['id'];
        } else {
            $data = OrderCart::query()->create($data);
            $cart_id = $data['id'];
        }

        return $cart_id;
    }

    public function delete($userInfo, $goods_id)
    {
        $where = [
            'user_id' => $userInfo['id'],
        ];
        if (!empty($goods_id)) {
            $where['goods_id'] = $goods_id;
        }

        return OrderCart::query()->where($where)->delete();
    }
}
