<?php

namespace App\Http\Middleware;

use Closure;

class AccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //验证token是否可用
        $access_token = $request->get('access_token');
        if (empty($token)) {
            echo "授权失败 缺少access_token";
            die;
        }

        //echo "token:".$token;die;
        $redis_hs_key = 'hs_access_token:'.$access_token;
        //echo "redis_h_token:".$redis_hs_key;

        //获取Redis哈希列表中的值
        $data = Redis::hGetAll($redis_hs_key);

        //var_dump($data);die;

        if (empty($data)) {
            echo "授权失败，access_token无效";
            die;
        }

        return $next($request);
    }
}
