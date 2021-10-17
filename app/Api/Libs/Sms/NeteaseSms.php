<?php

namespace App\Api\Libs\Sms;

/**
 * Created by PhpStorm.
 * User: zhm
 * Date: 2017/4/8
 * Time: 上午9:33
 *
 * 网易短信发送接口封装
 */
class NeteaseSms implements SmsInterface
{

    static private $WY_templateid_verify = "xxx"; //短信注册
//    static private $WY_templateid_verify = "xxx"; //短信注册
    static private $WY_templateid_notify = "xxx"; // 匹配消息通知
    static private $WY_appKey = 'xxx';
    static private $WY_AppSecret = 'xxx';
    static private $WY_nonce = 'xxx';


    function sendSMSForVerifyCode($mobile, $appid = 'com.taixue.xunji')
    {
        return self::sendSMS($mobile, self::$WY_templateid_verify);
    }

    /**
     * 发送短信验证
     * @param $mobile
     * @return mixed
     */
    static public function sendSMS($mobile, $templateid, $info = null)
    {

        $data = array(
            'mobile' => $mobile,
            'templateid' => $templateid
        ); // 定义参数
        if (!empty($info)) {
            $data['params'] = $info;
        }
        $data = @http_build_query($data); // 把参数转换成URL数据
        $curTime = time();
        $nonce = self::$WY_nonce;
        $appKey = self::$WY_appKey;
        $AppSecret = self::$WY_AppSecret;
        $aContext = array(
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'AppKey: ' . $appKey,
                    'CurTime: ' . $curTime,
                    'CheckSum: ' . sha1($AppSecret . $nonce . $curTime),
                    'Nonce: ' . $nonce,
                    'charset: utf-8'
                ),
                'content' => $data
            )
        );
        $cxContext = stream_context_create($aContext);
        $sUrl = 'https://api.netease.im/sms/sendcode.action';
        $d = @file_get_contents($sUrl, false, $cxContext);
        $tokenJson = json_decode($d, true);
        return $tokenJson;
    }

    /**
     *
     * @param array $mobile exmaple:$mobile = array('1367127373','136638394')
     * @param array $info example:$info = array('周四','匹配手机号')
     * @return mixed
     */
    function sendSMSForNotify($mobile, $info)
    {

        $param['templateid'] = self::$WY_templateid_notify;
        $param['mobiles'] = json_encode($mobile);
        $param['params'] = json_encode($info);
        $curTime = time();
        $header[] = 'Content-Type: application/x-www-form-urlencoded';
        $header[] = 'AppKey: ' . self::$WY_appKey;
        $header[] = 'CurTime: ' . $curTime;
        $header[] = 'CheckSum: ' . sha1(self::$WY_AppSecret . self::$WY_nonce . $curTime);
        $header[] = 'Nonce: ' . self::$WY_nonce;
        $header[] = 'charset: utf-8';
        $result = HttpUtil::httpPost('https://api.netease.im/sms/sendtemplate.action', $param, $header);
        return $result;
    }
}
