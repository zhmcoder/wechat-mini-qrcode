<?php

namespace App\Api\Libs\Sms;

use AlibabaCloud\Client\AlibabaCloud;
use App\Api\Libs\AliCloud;

/**
 * Created by PhpStorm.
 * User: zhm
 * Date: 2017/4/8
 * Time: 上午9:33
 *
 * 阿里短信发送接口封装
 */
class AliSms implements SmsInterface
{

    function sendSMSForVerifyCode($mobile, $appid = 'com.taixue.xunji')
    {
        if ($appid == 'com.taixuetv.yuantengfei') {
            $sign_name = '袁腾飞';
        } else {
            $sign_name = '循迹讲堂';
        }
        return AliCloud::send_sms($mobile, $sign_name);
    }

}
