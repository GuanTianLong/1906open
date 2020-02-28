<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;           //Redis

class ApiController extends Controller
{
    /**
        *测试Access Token
     */

        public function test1(){
            $data=[
                'user_name'   =>'wnagwu',
                'time'   =>date('Y-m-d H:i:s')
            ];
            return $data;
        }

}
