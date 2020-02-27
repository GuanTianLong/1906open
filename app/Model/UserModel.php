<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserModel extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'p_users';

    /**
     * 重定义主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 不可批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];


    /**
        * 为用户生成APPID
        *
        * 规则：MD5(用户名+时间戳+随机数)
     */
    public static function generateAppid($com_legal){

        return 'ln'.substr(md5($com_legal.time().mt_rand(11111,99999)),6,18);
    }

    /**
        *
        *为用户生成APP SECRET
        *
     */
    public static function generateSecret(){

        return Str::random(35);
    }

}
