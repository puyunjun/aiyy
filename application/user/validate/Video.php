<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\2\2 0002
 * Time: 10:36
 */

namespace app\user\validate;

use think\Validate;
class Video extends Validate
{

    protected $rule = [

        'video_url|文件选择'        =>'require',
    ];

    //定义验证提示
    protected $message = [
        'video_url.require' => '未选择文件',

    ];

    //定义验证场景
    protected $scene = [
        'edit'=> ['video_url'],
    ];


}