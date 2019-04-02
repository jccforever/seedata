<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
$path = request()->path();
$pathArr = explode('/', $path);
if (!in_array($pathArr[0], ['api', 'admin']) && $pathArr[0] !== 'addons') {
    \think\Route::bind('index');
}
// Route::rule('jd','index/Business/jingdong');
