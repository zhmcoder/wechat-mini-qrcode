<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\AdminUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property string|null $api_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUsername($value)
 * @mixin \Eloquent
 * @property string|null $openid
 * @property string|null $mobile
 * @property int $amount 账户余额
 * @property int $frozen_amount 冻结账号金额
 * @property int $sex 性别
 * @property string|null $country
 * @property string|null $province
 * @property string|null $city
 * @property string|null $language
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereFrozenAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereSex($value)
 */
class AdminUser extends Model
{
    protected $fillable = [
        'username', 'password', 'name', 'avatar', 'remember_token',
        'openid', 'mobile', 'amount', 'sex', 'country', 'province',
        'city', 'language','frozen_amount','alipay_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'password', 'remember_token'
    ];


}
