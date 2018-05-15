<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\broker\validate;

use think\Validate;
/**
 * 用户验证器
 * @package app\admin\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Signin extends Validate
{

    protected  $rule = [
        'phone'             => 'require',          // 电话号码
        'password'          => 'require',            //密码
    ];


    protected    $message = [
        'phone.require'        =>           '请填写电话号码',                    // 约游对象
        'password.require'            =>    '密码必须',                   // 出行方式
    ];
}

