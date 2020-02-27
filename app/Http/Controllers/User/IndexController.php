<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Hash;

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

        //获取APPID
        $str1 = '013abcde';
        $str2 = 'hjk89lp';
        $appid = str_shuffle($str1).mt_rand(11111,99999).str_shuffle($str2);
        //dd($appid);

        //获取SECRET
        $str3 = 'abcdefg12345hijklmn67890opqrstuvwxyz';
        $appsecret = str_shuffle($str3);
        //dd($appsecret);

        $user_data = [
            'com_name'              => $data['com_name'],
            'com_legal'             => $data['com_legal'],
            'com_address'           => $data['com_address'],
            'com_logo'              => $data['com_logo'],
            'com_mobile'            => $data['com_mobile'],
            'com_email'             => $data['com_email'],
            'com_password'          => $data['com_password'],
            'appid'                  => $appid,
            'appsecret'             => $appsecret
        ];

        //dd($user_data);

        //数据入库
        $res = UserModel::create($user_data);
        if($res){
            echo "<script>alert('注册成功');location.href='/user/login'</script>";
        }else{
            echo "<script>alert('注册失败');location.href='/user/register'</script>";
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


        echo "<script>alert('登录成功');location.href='/user/center'</script>";


    }

    /**
        *用户中心
     */
    public function center(){

        return view('user.center');
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
