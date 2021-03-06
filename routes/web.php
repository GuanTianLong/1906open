<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    echo date("Y-m-d H:i:s");
    return view('welcome');
});

//phpinfo
Route::get('/phpinfo', function () {
    phpinfo();
});

//用户路由分组
Route::prefix('/user')->group(function () {
    //用户注册
    Route::get('/register','User\IndexController@register');
    //执行用户注册
    Route::post('/register_do','User\IndexController@registerDo');
    //用户登录
    Route::get('/login','User\IndexController@login');
    //执行用户登录
    Route::post('/login','User\IndexController@loginDo');
    //用户中心
    Route::get('/center','User\IndexController@center');
    //接口(获取Access Token)
    Route::get('/getAccessToken','User\IndexController@getAccessToken');
});

//Api路由分组
Route::prefix('/api')->middleware('open.access_token')->group(function () {
    //测试Access Token
    Route::get('/test1','Api\ApiController@test1');
});

//GITHUB路由分组
Route::prefix('/github')->group(function () {
    //GITHUB登录(本地测试)
    Route::get('/index','GithubController@index');
    //用户授权回跳的页面
    Route::get('/callback1','GithubController@callback1');

});


