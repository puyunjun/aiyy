<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\5\3 0003
 * Time: 10:04
 */

namespace app\broker\validate;
use think\Validate;

class Account extends Validate
{

    protected  $rule = [
        'alipay_num'             => 'require',          // 支付宝帐号
        'alipay_name'          => 'require',            // 支付宝姓名
    ];


    protected    $message = [
        'alipay_num.require'        =>        '请填写支付宝帐号',
        'alipay_name.require'       =>    '请填写支付宝姓名',
    ];

}