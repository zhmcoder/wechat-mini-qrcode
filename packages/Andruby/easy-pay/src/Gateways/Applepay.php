<?php

namespace Yansongda\Pay\Gateways;

use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yansongda\Pay\Contracts\GatewayApplicationInterface;
use Yansongda\Pay\Contracts\GatewayInterface;
use Yansongda\Pay\Events;
use Yansongda\Pay\Exceptions\GatewayException;
use Yansongda\Pay\Exceptions\InvalidArgumentException;
use Yansongda\Pay\Exceptions\InvalidGatewayException;
use Yansongda\Pay\Exceptions\InvalidSignException;
use Yansongda\Pay\Gateways\ApplePay\Support;
use Yansongda\Pay\Log;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Config;
use Yansongda\Supports\Str;

/**
 * @method Response         app(array $config)          APP 支付
 * @method Collection       groupRedpack(array $config) 分裂红包
 * @method Collection       miniapp(array $config)      小程序支付
 * @method Collection       mp(array $config)           公众号支付
 * @method Collection       pos(array $config)          刷卡支付
 * @method Collection       redpack(array $config)      普通红包
 * @method Collection       scan(array $config)         扫码支付
 * @method Collection       transfer(array $config)     企业付款
 * @method RedirectResponse wap(array $config)          H5 支付
 */
class Applepay implements GatewayApplicationInterface
{
    /**
     * 普通模式.
     */
    const MODE_NORMAL = 'normal';

    /**
     * 沙箱模式.
     */
    const MODE_DEV = 'dev';

    /**
     * Const url.
     */
    const URL = [
        self::MODE_NORMAL => 'https://buy.itunes.apple.com/',
        self::MODE_DEV => 'https://sandbox.itunes.apple.com/',
    ];

    /**
     * Wechat payload.
     *
     * @var array
     */
    protected $payload;

    /**
     * Wechat gateway.
     *
     * @var string
     */
    protected $gateway;

    /**
     * Bootstrap.
     *
     * @throws Exception
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function __construct(Config $config)
    {
        $this->gateway = Support::create($config)->getBaseUri();
        $this->payload = [
        ];
    }

    /**
     * Magic pay.
     *
     * @param string $method
     * @param string $params
     *
     * @return Response|Collection
     * @throws InvalidGatewayException
     *
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function __call($method, $params)
    {
        return self::pay($method, ...$params);
    }

    /**
     * Pay an order.
     *
     * @param string $gateway
     * @param array $params
     *
     * @return Response|Collection
     * @throws InvalidGatewayException
     *
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function pay($gateway, $params = [])
    {
        Events::dispatch(new Events\PayStarting('Wechat', $gateway, $params));

        $this->payload = array_merge($this->payload, $params);

        $gateway = get_class($this) . '\\' . Str::studly($gateway) . 'Gateway';

        if (class_exists($gateway)) {
            return $this->makePay($gateway);
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] Not Exists");
    }

    /**
     * Verify data.
     *
     * @param string|null $content
     *
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function verify($receipt_data = null, bool $sandbox = false): Collection
    {
        $this->payload = json_encode(array("receipt-data" => $receipt_data));

        Events::dispatch(new Events\MethodCalled('Wechat', 'Refund', $this->gateway, $this->payload));

        return Support::requestApi(
            'verifyReceipt',
            $this->payload,
            false
        );
    }

    /**
     * Query an order.
     *
     * @param string|array $order
     *
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function find($order, string $type = 'wap'): Collection
    {
        return new Collection();
    }

    /**
     * Refund an order.
     *
     * @throws GatewayException
     * @throws InvalidSignException
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function refund(array $order): Collection
    {
        return new Collection();
    }

    /**
     * Cancel an order.
     *
     * @param array $order
     *
     * @throws GatewayException
     * @throws InvalidSignException
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function cancel($order): Collection
    {
        unset($this->payload['spbill_create_ip']);

        $this->payload = Support::filterPayload($this->payload, $order);

        Events::dispatch(new Events\MethodCalled('Wechat', 'Cancel', $this->gateway, $this->payload));

        return Support::requestApi(
            'secapi/pay/reverse',
            $this->payload,
            true
        );
    }

    /**
     * Close an order.
     *
     * @param string|array $order
     *
     * @throws GatewayException
     * @throws InvalidSignException
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function close($order): Collection
    {
        return new Collection();
    }

    /**
     * Echo success to server.
     *
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function success(): Response
    {
        Events::dispatch(new Events\MethodCalled('Wechat', 'Success', $this->gateway));

        return new Response(
            Support::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']),
            200,
            ['Content-Type' => 'application/xml']
        );
    }

    /**
     * Download the bill.
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @author yansongda <me@yansongda.cn>
     *
     */
    public function download(array $params): string
    {
        unset($this->payload['spbill_create_ip']);

        $this->payload = Support::filterPayload($this->payload, $params, true);

        Events::dispatch(new Events\MethodCalled('Wechat', 'Download', $this->gateway, $this->payload));

        $result = Support::getInstance()->post(
            'pay/downloadbill',
            Support::getInstance()->toXml($this->payload)
        );

        if (is_array($result)) {
            throw new GatewayException('Get Wechat API Error: ' . $result['return_msg'], $result);
        }

        return $result;
    }

    /**
     * Make pay gateway.
     *
     * @param string $gateway
     *
     * @return Response|Collection
     * @throws InvalidGatewayException
     *
     * @author yansongda <me@yansongda.cn>
     *
     */
    protected function makePay($gateway)
    {
        $app = new $gateway();

        if ($app instanceof GatewayInterface) {
            return $app->pay($this->gateway, array_filter($this->payload, function ($value) {
                return '' !== $value && !is_null($value);
            }));
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] Must Be An Instance Of GatewayInterface");
    }
}
