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

namespace app\user\validate;

use think\Validate;
/**
 * 用户验证器
 * @package app\admin\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Release extends Validate
{

    protected  $rule = [
           'release_object'       => 'require',          // 约游对象
            'travel_tool'          => 'require',            //出行方式
            'travel_start_time'    => 'require',      //出行时间
            'travel_total_time'     =>'require',          //出行天数
       ];


    protected    $message = [
            'release_object.require'        => '约游对象必须',                    // 约游对象
            'travel_tool.require'            => '出行方式必须',                   // 出行方式
            'travel_start_time.require'      => '出行时间必须',                //出行时间
            'travel_total_time.require'      => '出行天数必须',            //出行天数
        ];
}

