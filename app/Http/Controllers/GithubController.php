<?php

namespace App\Http\Controllers;

use App\Model\UserModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;                      //Guzzle
use App\Model\GithubUserModel;              //GithubUserModel
use Illuminate\Support\Str;                 //Str
use Illuminate\Support\Facades\Cookie;      //Cookie
use Illuminate\Support\Facades\Redis;       //Redis

class GithubController extends Controller
{
    /**
        *GITHUB登录(本地测试)
     */
    public function index(){

        return view('github.index');
    }

    /**
        *用户授权回跳的页面
     */
    public function callback1(){

        $client = new Client();

        //echo "<pre>";print_r($_GET);echo "</pre>";

        //获取到的code(github给我们的code，10分钟内有效)
        $code = $_GET['code'];

        //利用获取到的code ，去github接口获取Access Token
        $uri = "https://github.com/login/oauth/access_token";

        //利用guzzle的post方式获取
        $response = $client->request('POST',$uri,[

            //携带http headers(转为JSON格式)
            'headers' =>[
                'Accept' => 'application/json'
            ],

            'form_params' => [
                'client_id'         => env('GITHUB_CLIENT_ID'),
                'client_secret'     => env('GITHUB_CLIENT_SECRET'),
                'code'               => $code
            ]
        ]);

        $body = $response->getBody();
        //echo $body;echo "<hr>";

        //将JSON数据格式转化为数组
        $info = json_decode($body,'true');
        //echo "<pre>";print_r($info);echo "</pre>";

        //取出数据中的Access Token
        $access_token = $info['access_token'];
        //echo "Access Token：".$access_token;echo "<br>";

        //使用Access Token获取用户信息
        $uri1 = "https://api.github.com/user";

        $response1 = $client->request('GET',$uri1,[
            'headers' => [
                'Authorization' => 'OAUTH-TOKEN'
            ],

           'query' => [
                'access_token' => $access_token
           ]
       ]);

        $userInfo = $response1->getBody();
        //echo "<pre>";print_r($userInfo);echo "</pre>";

        //将获取到的数据转化为数组
        $userInfo = json_decode($userInfo,'true');
        //echo "<pre>";print_r($userInfo);echo "</pre>";

        //判断用户是否已经存在，如果不存在则将用户信息存入数据库
        $user = GithubUserModel::where(['github_id' => $userInfo['id']])->first();
        if($user){          //用户存在
            //echo "欢迎您回来！";echo "<br>";
        }else{              //用户不存在
            //在用户主表中记录用户信息
            $user_data = [
                'com_email' => $userInfo['email']
            ];

            //生成主表uid，关联p_users_github用户表
            $uid = UserModel::insertGetId($user_data);
            //echo "欢迎新用户！";echo "<br>";

            //将新用户的信息记录入p_users_github用户表中
            $github_user_info = [
                'uid'            => $uid,
                'github_id'     => $userInfo['id'],
                'location'      => $userInfo['location'],
                'email'         => $userInfo['email']
            ];

            $id = GithubUserModel::insertGetId($github_user_info);
            if($id > 0){

            }else{

            }
        }

        //生成用户的token标识,并返回给客户端（存入到cookie中）
        $user_token = Str::random(16);
        //echo "生成的token：".$user_token;

        Cookie::queue('Token',$user_token,60);

        //将生成的token保存至redis中
        $redis_hs_token = "redis_hs_token:".$user_token;

        //存入用户的信息
        $token_info = [
            'uid'            => $user['uid'],
            'login_time'    => date('Y-m-d H:i:s')
        ];

        //同时将多个field-value对设置到Redis的hash表中
        $arr=Redis::hMset($redis_hs_token,$token_info);

        //设置redis的过期时间(1小时)
        Redis::expire($redis_hs_token,60*60);

        header('Refresh:2;url=/user/center');
        echo "登录成功，正在跳转至个人中心....";


    }

}
