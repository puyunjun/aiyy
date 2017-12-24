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

namespace app\index\controller;
use think\cache\driver\Redis;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        if(false){
            $this->redirect('user/login/index');
        }
    }

    public function index()
    {
        // 默认跳转模块
        if (config('home_default_module') != 'index') {
            $this->redirect(config('home_default_module'). '/index/index');
        }

        $config = [
            'host'       => '127.0.0.1',
            'port'       => 6379,
            'password'   => '123456',
            'select'     => 0,
            'timeout'    => 0,
            'expire'     => 0,
            'persistent' => false,
            'prefix'     => '',
        ];

        $Redis=new Redis($config);
        //$Redis->set("test","test");
        //echo  $Redis->get("test");
        return $this->fetch();

    }
}