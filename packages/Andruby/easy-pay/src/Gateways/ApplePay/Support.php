<?php

namespace Yansongda\Pay\Gateways\ApplePay;

use Exception;
use Yansongda\Pay\Events;
use Yansongda\Pay\Exceptions\BusinessException;
use Yansongda\Pay\Exceptions\GatewayException;
use Yansongda\Pay\Exceptions\InvalidArgumentException;
use Yansongda\Pay\Exceptions\InvalidSignException;
use Yansongda\Pay\Gateways\ApplePay;
use Yansongda\Pay\Gateways\Wechat;
use Yansongda\Pay\Log;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Config;
use Yansongda\Supports\Str;
use Yansongda\Supports\Traits\HasHttpRequest;

/**
 * @author yansongda <me@yansongda.cn>
 *
 * @property string appid
 * @property string app_id
 * @property string miniapp_id
 * @property string sub_appid
 * @property string sub_app_id
 * @property string sub_miniapp_id
 * @property string mch_id
 * @property string sub_mch_id
 * @property string key
 * @property string return_url
 * @property string cert_client
 * @property string cert_key
 * @property array log
 * @property array http
 * @property string mode
 */
class Support
{
    use HasHttpRequest;

    /**
     * Wechat gateway.
     *
     * @var string
     */
    protected $baseUri;

    /**
     * Config.
     *
     * @var Config
     */
    protected $config;

    /**
     * Instance.
     *
     * @var Support
     */
    private static $instance;


    private static $status_message = ['21000' => 'App Store不能读取你提供的JSON对象',
        '21002' => 'receipt-data域的数据有问题',
        '21003' => 'receipt无法通过验证',
        '21004' => '提供的shared secret不匹配你账号中的shared secret',
        '21005' => 'receipt服务器当前不可用',
        '21007' => 'receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送',
        '21006' => 'receipt是Sandbox receipt，但却发送至生产系统的验证服务',
        '21008' => 'receipt是生产receipt，但却发送至Sandbox环境的验证服务'
    ];

    /**
     * Bootstrap.
     *
     * @author yansongda <me@yansongda.cn>
     */
    private function __construct(Config $config)
    {
        $this->baseUri = ApplePay::URL[$config->get('mode', ApplePay::MODE_NORMAL)];
        $this->config = $config;

        $this->setHttpOptions();
    }

    /**
     * __get.
     *
     * @param $key
     *
     * @return mixed|Config|null
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function __get($key)
    {
        return $this->getConfig($key);
    }

    /**
     * create.
     *
     * @return Support
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     *
     * @throws GatewayException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function create(Config $config)
    {
        if ('cli' === php_sapi_name() || !(self::$instance instanceof self)) {
            self::$instance = new self($config);

//            self::setDevKey();
        }

        return self::$instance;
    }

    /**
     * getInstance.
     *
     * @return Support
     * @throws InvalidArgumentException
     *
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            throw new InvalidArgumentException('You Should [Create] First Before Using');
        }

        return self::$instance;
    }

    /**
     * clear.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public static function clear()
    {
        self::$instance = null;
    }

    /**
     * Request wechat api.
     *
     * @param string $endpoint
     * @param array $data
     * @param bool $cert
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function requestApi($endpoint, $data, $cert = false): Collection
    {
        Events::dispatch(new Events\ApiRequesting('Wechat', '', self::$instance->getBaseUri() . $endpoint, $data));

        $result = self::$instance->post(
            $endpoint,
            $data,
            $cert ? [
                'cert' => self::$instance->cert_client,
                'ssl_key' => self::$instance->cert_key,
            ] : []
        );

        Events::dispatch(new Events\ApiRequested('Wechat', '', self::$instance->getBaseUri() . $endpoint, $result));

        return self::processingApiResult($endpoint, $result);
    }

    /**
     * Filter payload.
     *
     * @param array $payload
     * @param array|string $params
     * @param bool $preserve_notify_url
     *
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function filterPayload($payload, $params, $preserve_notify_url = false): array
    {
        $type = self::getTypeName($params['type'] ?? '');

        $payload = array_merge(
            $payload,
            is_array($params) ? $params : ['out_trade_no' => $params]
        );
        $payload['appid'] = self::$instance->getConfig($type, '');


        unset($payload['trade_type'], $payload['type']);
        if (!$preserve_notify_url) {
            unset($payload['notify_url']);
        }

        $payload['sign'] = self::generateSign($payload);

        return $payload;
    }

    /**
     * Generate wechat sign.
     *
     * @param array $data
     *
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function generateSign($data): string
    {
        $key = self::$instance->key;

        if (is_null($key)) {
            throw new InvalidArgumentException('Missing Wechat Config -- [key]');
        }

        ksort($data);

        $string = md5(self::getSignContent($data) . '&key=' . $key);

        Log::debug('Wechat Generate Sign Before UPPER', [$data, $string]);

        return strtoupper($string);
    }

    /**
     * Generate sign content.
     *
     * @param array $data
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function getSignContent($data): string
    {
        $buff = '';

        foreach ($data as $k => $v) {
            $buff .= ('sign' != $k && '' != $v && !is_array($v)) ? $k . '=' . $v . '&' : '';
        }

        Log::debug('Wechat Generate Sign Content Before Trim', [$data, $buff]);

        return trim($buff, '&');
    }


    /**
     * Convert array to xml.
     *
     * @param array $data
     *
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function toXml($data): string
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new InvalidArgumentException('Convert To Xml Error! Invalid Array!');
        }

        $xml = '<xml>';
        foreach ($data as $key => $val) {
            $xml .= is_numeric($val) ? '<' . $key . '>' . $val . '</' . $key . '>' :
                '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * Get service config.
     *
     * @param string|null $key
     * @param mixed|null $default
     *
     * @return mixed|null
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function getConfig($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config->all();
        }

        if ($this->config->has($key)) {
            return $this->config[$key];
        }

        return $default;
    }

    /**
     * Get app id according to param type.
     *
     * @param string $type
     * @author yansongda <me@yansongda.cn>
     *
     */
    public static function getTypeName($type = ''): string
    {
        switch ($type) {
            case '':
                $type = 'app_id';
                break;
            case 'app':
                $type = 'appid';
                break;
            default:
                $type = $type . '_id';
        }

        return $type;
    }

    /**
     * Get Base Uri.
     *
     * @return string
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * processingApiResult.
     *
     * @param $endpoint
     *
     * @return Collection
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     *
     * @throws GatewayException
     * @author yansongda <me@yansongda.cn>
     *
     */
    protected static function processingApiResult($endpoint, array $result)
    {
        if ($result['status'] !== 0) {
            Events::dispatch(new Events\SignFailed('Applepay', '', $result));
            $result['message'] = self::$status_message[$result['status']];
        }
        return new Collection($result);
    }

    /**
     * Set Http options.
     *
     * @author yansongda <me@yansongda.cn>
     */
    private function setHttpOptions(): self
    {
        if ($this->config->has('http') && is_array($this->config->get('http'))) {
            $this->config->forget('http.base_uri');
            $this->httpOptions = $this->config->get('http');
        }

        return $this;
    }
}
