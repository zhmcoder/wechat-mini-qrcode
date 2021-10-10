<?php

namespace App\Api\Middleware;

use Closure;
use Response;

class VerifySign
{
    private $app_id = 'cms20200728001';
    private $app_secret = 'oee3936a247db9882c2';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_api_sign = request('api_sign', null);

        $except = ['api_sign', 'files', 'file'];

        $api_sign = $this->api_sign($request->except($except));
        if ($request_api_sign === $api_sign) {
            return $next($request);
        }
        error_log_info('api sign error request: ' . json_encode($request->all()));
        $data = ['status' => 10000, 'message' => 'api sign error'];

        return Response::json($data);

    }

    protected function api_sign($data, $raw = null)
    {
        krsort($data);

        $str = '';
        foreach ($data as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        $str = trim($str, '&');
        $sign = strtolower(md5(md5('com.taixue.xunji') . $str));

        return $sign;
    }
}
