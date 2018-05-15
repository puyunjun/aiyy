<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\4\28 0028
 * Time: 15:19
 */

namespace app\broker\validate;

use think\Validate;
class MyInfo extends Validate
{


    protected  $rule = [
        'username'             => 'require',          // 用户名
    ];


    protected    $message = [
        'username.require'        =>        '请填写用户名',                    // 约游对象
    ];
}