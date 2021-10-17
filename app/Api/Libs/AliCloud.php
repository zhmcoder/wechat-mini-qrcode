<?php


namespace App\Api\Libs;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliCloud
{

    private const ACCESS_KEY_ID = 'xxxx';
    private const ACCESS_KEY_SECRET = 'xxxx';

    public static function get_mobile($accessToken = '')
    {
        $mobile = null;
        AlibabaCloud::accessKeyClient(AliCloud::ACCESS_KEY_ID, AliCloud::ACCESS_KEY_SECRET)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dypnsapi')
                ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('GetMobile')
                ->method('POST')
                ->host('dypnsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'AccessToken' => $accessToken,
                    ],
                ])
                ->request();
            $result = $result->toArray();
            if ($result['Message'] == 'OK') {
                $mobile = $result['GetMobileResultDTO']['Mobile'];
            } else {
                error_log_info('ali login error result = ' . json_encode($result) . ' access_token = ' . $accessToken);
            }
        } catch (ClientException $e) {
            error_log_info('ali login error msg = ' . $e->getErrorMessage());
        } catch (ServerException $e) {
            error_log_info('ali login error msg = ' . $e->getErrorMessage());
        }
        return $mobile;
    }

    public static function send_sms($mobile, $sign_name)
    {
        AlibabaCloud::accessKeyClient(AliCloud::ACCESS_KEY_ID, AliCloud::ACCESS_KEY_SECRET)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
        $status['code'] = -1;

        try {

            $param['code'] = mt_rand(1001, 9999);
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $mobile,
                        'SignName' => $sign_name,
                        'TemplateCode' => "SMS_205391430",
                        'TemplateParam' => json_encode($param),
                    ],
                ])
                ->request();
            if ($result) {
                $result = $result->toArray();
                if ($result['Code'] == 'OK') {
                    $status['code'] = '200';
                    $status['obj'] = $param['code'];
                } elseif ($result['code'] = 'isv.BUSINESS_LIMIT_CONTROL') {
                    error_log_info('ali send sms request limit ' . ' mobile = ' . $mobile);
                    $status['code'] = 4;
                } else {
                    error_log_info('ali send sms request msg = ' . json_encode($result) . ' mobile = ' . $mobile);
                    $status['code'] = -1;
                }
            } else {
                error_log_info('ali send sms request error msg' . ' mobile = ' . $mobile);
                $status['code'] = -1;
            }
        } catch (ClientException $e) {
            error_log_info('ali send sms error msg = ' . $e->getErrorMessage() . ' mobile = ' . $mobile);
        } catch (ServerException $e) {
            error_log_info('ali send sms error msg = ' . $e->getErrorMessage() . ' mobile = ' . $mobile);
        }
        return $status;
    }
}
