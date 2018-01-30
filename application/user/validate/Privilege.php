<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\29 0029
 * Time: 11:45
 */

namespace app\user\validate;

use think\Validate;
class Privilege extends Validate
{

    // 定义验证规则
    protected $rule = [
        //'tid|企业所属类型'    => 'require',
        'group_id|分组名称'   => 'require|unique:user_group_privilege',
    ];
    protected $message  = [
        'group_id.require' => '请选择分组名称',
        'group_id.unique' => '该分组权限已存在',
    ];
    protected $scene = [
        'add'   => ['group_id'],
        'edit'  =>  ['group_id'=>'require'],
    ];
}