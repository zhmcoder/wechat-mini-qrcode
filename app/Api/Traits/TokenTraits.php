<?php

namespace App\Api\Traits;

use App\Models\UserToken;
use Cache;
use Str;

trait TokenTraits
{
    protected function genToken($user_id)
    {
        $token = Str::random(60);
        $data['user_id'] = $user_id;
        $data['api_token'] = $token;
        $data['expire_at'] = time() + config('tokens-auth.token_expire_time', 6 * 30 * 24 * 60 * 60);

        UserToken::create($data);
        $this->resetToMaxActiveTokens($user_id);
        return $token;
    }

    protected function userInfo()
    {
        return ['id' => 1];
        $token = request('token');
        if (empty($token)) {
            $status['status'] = 1001;
            $status['msg'] = '请重新登录';
        } else {
            $user_info = null;

            $token_info = UserToken::where('api_token', $token)->with('user')->first();
            if ($token_info && $token_info['user']) {
                return $token_info['user'];
            } else {
                $status['status'] = 1001;
                $status['msg'] = '请重新登录';
            }
        }
        $this->response($status);
    }

    protected function userId()
    {
        $token = request('token');
        if (!empty($token)) {
            $user_info = Cache::get('user_' . $token);
            if ($user_info) {
                return $user_info['id'];
            } else {
                $token_info = UserToken::where('api_token', $token)->with('user')->first();
                if ($token_info && $token_info['user']) {
                    return $token_info['user']['id'];
                }
            }
        }
        return false;
    }

    protected function resetToMaxActiveTokens($user_id): void
    {
        $totalActiveTokens = config('tokens-auth.active_tokens', 2);
        $count = UserToken::where('user_id', $user_id)->count();

        if ($totalActiveTokens !== null && $count > $totalActiveTokens) {
            UserToken::where('user_id', $user_id)
                ->orderBy('created_at')
                ->skip($totalActiveTokens)
                ->take($count - $totalActiveTokens)
                ->delete();
        }
    }

}
