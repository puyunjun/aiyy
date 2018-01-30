<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\30 0030
 * Time: 11:24
 */

namespace app\user\validate;

use think\Validate;
class PrivilegeGroup extends Validate
{

    // 定义验证规则
    protected $rule = [
        //'tid|企业所属类型'    => 'require',
        'group_name|分组名称'   => 'require|unique:user_group',
        'member_type' => 'require|integer',
        'price_y'=>'require|float',
        'price_m'=>'require|float',
        'price_a'=>'require|float',
        'discount_y'=>'require|float',
        'discount_m'=>'require|float',
        'discount_a'=>'require|float',
        'prestore'=>'require|float',
        'gift_money'=>'require|float',
        'discount_pre'=>'require|float',
        'icon'=>'require',
        'usernamecolor'=>'require',
    ];
    protected $message  = [
        'member_type.require' =>'请选择会员类型',
        'price_y.require' => '月费价格不能为空',
        'price_m.require' => '半年费价格不能为空',
        'price_a.require' => '年费价格不能为空',
        'price_y.float' => '月费价格为浮点数',
        'price_m.float' => '半年费价格为浮点数',
        'price_a.float' => '年费价格为浮点数',
        'prestore.require' => '预存费价格不能为空',
        'gift_money.require' => '赠送费价格不能为空',
        'discount_y.require' => '月费折扣不能为空',
        'discount_m.require' => '半年费折扣不能为空',
        'discount_a.require' => '年费折扣不能为空',
        'discount_pre.require' => '预存制折扣不能为空',
        'icon.require' => '会员图标添加',
        'usernamecolor.require' => '请选择会员颜色',
    ];
    protected $scene = [
        'add1'   => [
            'group_name'=>'require|unique:user_group',
            'member_type'=>'require',
            'price_y'=>'require|float',
            'price_m'=>'require|float',
            'price_a'=>'require|float',
            'discount_y'=>'require|float',
            'discount_m'=>'require|float',
            'discount_a'=>'require|float',
            'icon'=>'require',
            'usernamecolor'=>'require',
        ],
        'add2'   => [
            'group_name'=>'require|unique:user_group',
            'prestore'=>'require|float',
            'gift_money'=>'require|float',
            'discount_pre'=>'require|float',
        ],
        'edit'  =>  ['group_id'=>'require'],
    ];

}