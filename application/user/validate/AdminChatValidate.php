<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\5\2 0002
 * Time: 15:55
 */

namespace app\user\validate;

use think\Validate;
class AdminChatValidate extends Validate
{
    // 定义验证规则
    protected $rule = [
        //'tid|企业所属类型'    => 'require',
        'admin_uid|管理员'   => 'require',
        'uid|系统会员'   => 'require',
    ];
    protected $message  = [
        'admin_uid.require' => '选择管理员',
        'uid.require' => '至少选择一个系统会员',
    ];
    protected $scene = [
        'add'   => ['admin_uid'],
        'edit'  =>  ['uid'=>'require'],
    ];
}