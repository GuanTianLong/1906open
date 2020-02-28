<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;                //UserModel
use App\Model\AppModel;                 //AppModel
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;   //Cookie
use Illuminate\Support\Facades\Redis;    //Redis

class IndexController extends Controller
{
    /**
     * 用户注册
     */
    public function register(){

        return view('user.register');
    }

    /**
        * 执行用户注册
     */
    public function registerDo(Request $request){

        $data = $request->input();

        //文件上传
        if($request->hasFile('com_logo') ){

            $data['com_logo'] = $this->upload('com_logo');
        }

        $pass = $data['com_password'];
        $confirm_pass = $data['confirm_pass'];

        if($confirm_pass !== $pass){
            echo "确认密码与密码不一致";die;
        }

        //使用门面Hash中make()方法来将密码进行加密。
        $data['com_password'] = Hash::make($pass);
        $com_legal = $data['com_legal'];

        $user_data = [
            'com_name'              => $data['com_name'],
            'com_legal'             => $com_legal,
            'com_address'           => $data['com_address'],
            'com_logo'              => $data['com_logo'],
            'com_mobile'            => $data['com_mobile'],
            'com_email'             => $data['com_email'],
            'com_password'          => $data['com_password'],
        ];
        //dd($user_data);

        //将用户注册写入数据p_users表中
        $res = UserModel::create($user_data);
        if(!empty($res)){
            header('Refresh:3;url=/user/login');
            echo "注册成功";
        }else{
            header('Refresh:3;url=/user/register');
            echo "注册失败";
        }

        //为用户生成APPID
        $appid = UserModel::generateAppid($com_legal);
        //echo "为用户生成的APPID：".$appid;echo "<br>";

        //为用户生成APP SECRET
        $app_secret = UserModel::generateSecret();
        //echo "为用户生成的APP SECRET：".$app_secret;

        //将APPID和APPSECRET写入数据库p_app表中
        $appInfo = [
            'uid'           => $res['id'],
            'app_id'        => $appid,
            'app_secret'    => $app_secret
        ];

        $result = AppModel::create($appInfo);
        //dd($result);
        if(!empty($result)){
            echo "OK";
        }else{
            echo "服务器内部错误，请尽快联系管理员解决";
        }

    }

    /**
        * 用户登录
     */
    public function login(){

        return view('user.login');
    }

    /**
        *执行用户登录
     */
    public function loginDo(Request $request){
        //接收用户名
        $uesr_name = $request->input('user_name');

        //根据用户名在数据库中进行查询
        $user_info = UserModel::where(['com_mobile' => $uesr_name])->orWhere(['com_email'  => $uesr_name])->first();
        //dd($user_info);

        //判断数据库中是否能查到
        if($user_info == null){
            echo "此用户不存在，请您先注册";
            die;
        }

        //接收密码
        $password = $request->input('password');

        //使用门面Hash中check()方法，进行验证，对比当前密码和数据库加密之后的密码是否相同。
       if(!Hash::check($password,$user_info['com_password'])){
           echo "密码有误";
           die;
       }

       //生成用户的token标识,并返回给客户端（存入到cookie中）
        $user_token = Str::random(16);
        echo "生成的token：".$user_token;

        Cookie::queue('Token',$user_token,60);

        //将生成的token保存至redis中
        $redis_hs_token = "redis_hs_token:".$user_token;

        //存入用户的信息
        $token_info = [
            'uid'            => $user_info['id'],
            'com_legal'     => $user_info['com_legal'],
            'login_time'    => date('Y-m-d H:i:s')
        ];


        ////同时将多个field-value对设置到Redis的hash表中
        $arr=Redis::hMset($redis_hs_token,$token_info);

        //设置redis的过期时间(1小时)
        Redis::expire($redis_hs_token,60*60);


        echo "<script>alert('登录成功');location.href='/user/center'</script>";


    }

    /**
        *用户个人中心
     */
    public function center(){
        //取出cookie中的token
        $token = Cookie::get('Token');
        //echo "Cooke中取出的token：".$token;

        //判断Cookie中是否有token值
        if(empty($token)){
            header('Refresh:2;url=/user/login');
            echo "请您先登录！";die;
        }

        //得到token,拼接redis的key
        $redis_hs_token = 'redis_hs_token:'.$token;
        //echo "Redis的key：".$redis_hs_token;echo "<br>";

        //从redis中获取hash表中的所有数据
        $redis_hs_info = Redis::hgetAll($redis_hs_token);
        //print_r($redis_hs_info);echo "<br>";

        //获取用户信息
        $appInfo = AppModel::where(['uid' => $redis_hs_info['uid']])->first()->toArray();
        //echo "<pre>";print_r($appInfo);echo "</pre>";

        echo "欢迎来到".$redis_hs_info['com_legal']."个人中心";echo "<hr>";
        echo "APPID：".$appInfo['app_id'];echo "<br>";
        echo "APP SECRET：".$appInfo['app_secret'];echo "<br>";



    }


    /**
        *获取Access Token(并记录有效期)
     */
    public function getAccessToken(Request $request){

        $appid          = $request->get('app_id');
        $app_secret     = $request->get('app_secret');

        //判断是否有参数
        if(empty($appid) || empty($app_secret)){
            echo "缺少参数！请输入参数.....";
            die;
        }

        $appInfo = AppModel::where(['app_id' => $appid])->first()->toArray();
        //echo "<pre>";print_r($appInfo);echo "</pre>";die;
        if($app_secret != $appInfo['app_secret']){
            echo "参数有误！请您输入正确的参数.....";
            die;
        }

        //echo "APPID：".$appid;echo "<br>";
        //echo "APP SECRET：".$app_secret;echo "<br>";

        //为用户生成Access Token(供后续接口调用)
        $str = $appid.$app_secret.time().mt_rand().Str::random(16);
        //echo "Str：".$str;echo "<br>";

        //对Access Token进行加密(为了防止冲突)
        $access_token = sha1($str).md5($str);
        //echo "Access Token：".$access_token;echo "<hr>";

        //拼接Redis的key
        $redis_hs_key = 'hs_access_token:'.$access_token;
        //echo "redis_hs_key：".$redis_hs_key;

        //存入信息
        $appInfo = [
            'appid'         => $appid,
            'add_time'      => date('Y-m-d H:i:s')
        ];

        //同时将多个field-value对设置到Redis的hash表中
        Redis::hMset($redis_hs_key,$appInfo);

        //设置Redis的过期时间(1小时)
        Redis::expire($redis_hs_key,7200);

        //响应结果
        $response = [
                'errno'             => 0,                            //错误码
                'Access Token'     => $access_token,                //Access Token
                'expire'            => 7200                         //过期时间
        ];

        return $response;

    }



    /**
        *文件上传
     */
    public function upload($file){
        if (request()->file($file)->isValid()) {
            $photo = request()->file($file);
            $store_result = $photo->store('upload');
            //$store_result = $photo->storeAs('photo', 'test.jpg');

            return $store_result;
        }
        exit('未获取到上传文件或上传过程出错');
    }

}
