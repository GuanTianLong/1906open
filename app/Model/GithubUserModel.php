<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GithubUserModel extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'p_users_github';

    /**
     * 重定义主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

}
