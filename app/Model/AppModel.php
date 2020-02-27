<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AppModel extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'p_app';

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

}
