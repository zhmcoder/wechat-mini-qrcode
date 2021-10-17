<?php


namespace App\Api\Libs\Sms;


interface SmsInterface
{
    function sendSMSForVerifyCode($mobile, $appid = 'com.taixue.xunji');
}
