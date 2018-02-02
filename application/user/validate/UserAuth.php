<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\31 0031
 * Time: 15:23
 */

namespace app\user\validate;

use think\Validate;
class UserAuth extends Validate
{

    protected $regex = [ 'phone' => '/^1[34578]{1}\d{9}$/'];
    //定义验证规则
    protected $rule = [
        'identifier|帐号信息' => 'require|regex:phone|unique:user_auth',
        'credential|登录密码'  => 'require|length:6,20',

        'group_id|会员分组'     =>'require',
        'phone|绑定号码'        =>'require|unique:_user_',
        'sex|性别'             =>'require',
        'nickname|昵称'        =>'require',
        'head_img|头像'        =>'require',
    ];

    //定义验证提示
    protected $message = [
        'identifier.require' => '请输入帐号信息',
        'identifier.unique'     => '该帐号已存在',
        'identifier.regex'     => '帐号只能是手机号',
        'credential.require' => '密码不能为空',
        'credential.length'  => '密码长度6-20位',

        'group_id.require'  =>'请选择权限分组',
        'phone.require'  =>'手机号码未绑定',
        'phone.unique'  =>'手机已经存在',
        'sex.require'  =>'请选择性别',
        'nickname.require'  =>'请输入昵称',
        'head_img.require'  =>'请上传头像',
    ];

    //定义验证场景
    protected $scene = [
        //创建帐号
        'auth'  =>  ['identifier','credential'],

        'info'  => ['group_id','phone','sex','nickname','head_img'],

        'edit'=> ['group_id','phone|require','sex','nickname','head_img'],
        ];


    

}