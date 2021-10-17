<?php

namespace App\Api\Controllers;

use App\Api\Libs\Sms\SmsHelper;
use App\Api\Libs\Verify\ImgCode;
use App\Api\Services\XcxService;
use App\Api\Validates\LoginValidate;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cache;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseController
{

    /**
     * 微信小程序登录
     * @param Request $request
     * @param LoginValidate $validate
     * @return \Illuminate\Http\Response|void
     */
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
     * 微信小程序登录
     * @param Request $request
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

    public function verify_code(Request $request, LoginValidate $validate)
    {
        $validate_result = $validate->mobile($request->only(['mobile']));
        if ($validate_result) {
            //获取最新的一条短信记录
            $img_code = $request->input('img_code');
            $mobile = $request->input('mobile');
            $smsRecord = VerifyCode::where('mobile', $mobile)
                ->where('created_at', '>=', strtotime(date('Y-m-d')))
                ->orderBy('id', 'desc')->first();
            $expire = $smsRecord && $smsRecord['status'] == '1' && (time() - $smsRecord['created_at']) < 60;
            if ($img_code) {
                //check img code
                if ($expire) {
                    //判断有效期
                    $this->responseJson('-1', '验证码还在有效期');
                } else {
                    if (ImgCode::verify_img_code(md5(config('lehui.aes_key') . $mobile), $img_code)) {
                        if (!SmsHelper::sendVerifyCode($request->input('mobile'), $request->getClientIp())) {
                            $this->responseJson('-1', '获取验证码失败');
                        }
                    } else {
                        $this->responseJson('-1', '图形验证码错误');
                    }
                }

            } else {
                if ($expire) {
                    //判断有效期
                    $this->responseJson('-1', '验证码还在有效期');
                } else {
                    if ($smsRecord) {
                        $data['img_code'] = \route('img.gen_img_code', ['img_id' => md5(config('lehui.aes_key') . $mobile)]);;
                        $this->responseJson('1002', '成功获取图形验证码', $data);
                    } else {
                        if (!SmsHelper::sendVerifyCode($request->input('mobile'), $request->getClientIp())) {
                            $this->responseJson('-1', '获取验证码失败');
                        }
                    }

                }
            }
        } else {
            return $this->responseJson('-1', $validate->message);
        }
        $this->responseJson('0', 'success');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     * @throws ValidationException
     */
    public function mobile(Request $request, LoginValidate $validate)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
        $validate_result = $validate->login($request->only(['mobile', 'verify_code']));
        if ($validate_result) {
            return $this->attempLogin($request);
        } else {
            return $this->responseJson('-1', $validate->message);
        }
    }
}

