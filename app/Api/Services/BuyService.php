<?php

namespace App\Api\Services;

use App\Api\Traits\ApiResponseTraits;

/**
 * @method static BuyService instance()
 *
 * Class BuyService
 * @package App\Api\Services
 */
class BuyService
{
    use ApiResponseTraits;

    public static function __callStatic($method, $params): BuyService
    {
        return new self();
    }

    /**
     * 购买信息
     * @param null $user_id
     * @param null $cart_ids
     * @return array
     */
    public function buy_info($user_id, $cart_ids)
    {
        $total = 0;

        $buy_info['total'] = $total;

        return $buy_info;
    }

}
