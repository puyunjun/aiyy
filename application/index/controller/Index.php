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
use app\user\model\home\User As UserModel;
use think\Session;

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

        /*$config = [
            'host'       => '127.0.0.1',
            'port'       => 6379,
            'password'   => '123456',
            'select'     => 0,
            'timeout'    => 0,
            'expire'     => 0,
            'persistent' => false,
            'prefix'     => '',
        ];

        $Redis=new Redis($config);*/
        //$Redis->set("test","test");
        //echo  $Redis->get("test");


        return $this->fetch();

    }


    public function test(){


       $a = '{
                "name":"北京",
                "code":"110000",
                "sub": [
                    {
                        "name": "北京市",
                        "code": "110000",
                        "sub":[
                            {
                                "name":"东城区",
                                "code":"110101"
                            },
                            {
                                "name":"西城区",
                                "code":"110102"
                            },
                            {
                                "name":"朝阳区",
                                "code":"110105"
                            },
                            {
                                "name":"丰台区",
                                "code":"110106"
                            },
                            {
                                "name":"石景山区",
                                "code":"110107"
                            },
                            {
                                "name":"海淀区",
                                "code":"110108"
                            },
                            {
                                "name":"门头沟区",
                                "code":"110109"
                            },
                            {
                                "name":"房山区",
                                "code":"110111"
                            },
                            {
                                "name":"通州区",
                                "code":"110112"
                            },
                            {
                                "name":"顺义区",
                                "code":"110113"
                            },
                            {
                                "name":"昌平区",
                                "code":"110114"
                            },
                            {
                                "name":"大兴区",
                                "code":"110115"
                            },
                            {
                                "name":"怀柔区",
                                "code":"110116"
                            },
                            {
                                "name":"平谷区",
                                "code":"110117"
                            },
                            {
                                "name":"密云县",
                                "code":"110228"
                            },
                            {
                                "name":"延庆县",
                                "code":"110229"
                            }
                        ]
                    }
                ]
            }';

      $m = json_decode($a, true);
       echo json_encode($m);
      exit;
    }


    public function ossup(){
        return $this->fetch();
    }

    public function ossserver(){
        //require_once VENDOR_PATH.'/sts-server/sts.php';   //已配置好  windows环境可用
        //调用搭建的服务函数获取临时帐号
      $ossup =  new StsServer();
      $ossup->index();
    }

}
