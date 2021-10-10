<?php

namespace App\Api\Controllers;

use App\Api\Libs\WXBizDataCrypt;
use App\Api\Services\BusinessService;
use App\Api\Services\LoginService;
use App\Api\Services\XcxService;
use App\Api\Validates\BusinessValidate;
use App\Api\Validates\LoginValidate;
use App\Models\AdminUser;
use App\Models\Business;
use App\Models\JoinInfo;
use App\Models\SpreadRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cache;

class LoginController extends BaseController
{

    public function login_xcx(Request $request, LoginValidate $validate)
    {
        $validate_result = $validate->xcxLogin($request->only(['code', 'loginInfo']));
        if ($validate_result) {
            return $this->attempWxXcx($request);
        } else {
            return $this->responseJson('-1', $validate->message);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     */
    protected function attempWxXcx(Request $request)
    {
        $code = $request->input('code');
        $loginInfo = $request->input('loginInfo');

        $loginInfo = json_decode($loginInfo, true);

        $wxSession = XcxService::getXcxSession($request->input('appid'),
            config('lehui.' . $request->input('appid') . '.appsecret'), $code);

        if (!$wxSession) {
            $this->responseJson($this::STATUS_MSG, "微信授权失败,请重新授权。", null);
        }

        $user = AdminUser::where('openid', $wxSession['openid'])->first();

        if ($user) {
            if ($user['mobile']) {
                $user['bind_mobile'] = 1;
            } else {
                $user['bind_mobile'] = 0;
            }
            if ($user['alipay_id']) {
                $user['bind_alipay'] = 1;
            } else {
                $user['bind_alipay'] = 0;
            }
            unset($user['mobile']);
            unset($user['username']);
            unset($user['openid']);
            unset($user['alipay_id']);

            $user['amount'] = round($user['amount'] / 100, 2);
            $user['frozen_amount'] = round($user['frozen_amount'] / 100, 2);

            $user['token'] = $this->genToken($user['id']);
            $this->responseJson('0', 'success', $user);
        } else {

            if (key_exists('encryptedData', $loginInfo) && key_exists('iv', $loginInfo)) {
                $user_info = XcxService::decryptData(request('appid'), $wxSession['openid'], $loginInfo['encryptedData'], $loginInfo['iv']);
                debug_log_info('user info = ' . json_encode($user_info));
            } else {
                $user_info = $loginInfo['userInfo'];
            }
            $user_info['openId'] = $wxSession['openid'];
            if ($user_info == '1001') {
                $this->responseJson('1001', '需要登录');
            }

            $user_data['openid'] = $user_info['openId'];
            $user_data['name'] = $user_info['nickName'];
            $user_data['avatar'] = $user_info['avatarUrl'];
            $user_data['sex'] = $user_info['gender'];
            $user_data['country'] = $user_info['country'];
            $user_data['province'] = $user_info['province'];
            $user_data['city'] = $user_info['city'];
            $user_data['language'] = $user_info['language'];
            $user_data['user_type'] = 1;

            $user_data = AdminUser::create($user_data);
            if ($user_data['id']) {
                $user_data['bind_mobile'] = 0;
                $user_data['bind_alipay'] = 0;
                $user_data['token'] = $this->genToken($user_data['id']);
                LoginService::instance()->spread_info($user_data['id']);
                $this->responseJson('0', 'success', $user_data);
            } else {
                $this->responseJson('-1', '微信登录失败');
            }
        }
    }
}

