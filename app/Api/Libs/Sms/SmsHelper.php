<?php


namespace App\Api\Libs\Sms;

use App\Api\Services\VerifyCodeService;

class SmsHelper
{
    private const APP_STORE_MOBILE = ['13581714393', '13581714400'];

    public static function sendVerifyCode($mobile, $clientIp = null)
    {
        $appid = request('appid', '');
        if (in_array($mobile, SmsHelper::APP_STORE_MOBILE)) {
            $data['code'] = 200;
            $data['obj'] = '1111';
        } else {
//            $sms = new NeteaseSms();
            $sms = new AliSms();
            $data = $sms->sendSMSForVerifyCode($mobile, $appid);
        }
        if ($data['code'] == 200) {
            VerifyCodeService::saveVerifyCode($mobile, $data['obj'], 0, $clientIp);
            $sendResult = true;
        } else {
            $sendResult = true;
        }
        return $sendResult;
    }

    public static function checkVerifyCode($mobile, $verifyCode, $clientIp = null)
    {
        $verifyService = new VerifyCodeService();
        return $verifyService->checkVerifyCode($mobile, $verifyCode, $clientIp);
    }
}
