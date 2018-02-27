<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\15 0015
 * Time: 16:42
 */

namespace app\user\home;

use function foo\func;
use think\Controller;
use app\user\model\home\UpgradeMember;
use app\user\model\home\Recharge;
use think\Db;
class News extends Common
{


    public function _initialize()
    {

        parent::_initialize(); // TODO: Change the autogenerated stub

        $this->assign('title','消息页面');
    }

    public function index(){

        //echo "<script>alert('".lang('Parse error')."')</script>";   //测试语言包助手函数
        //查询用户所有消息

        $model_name = array('Recharge','UpgradeMember');
        /*
         * @param $model_name  模型类名,url参数名
         * */
        $data = function($model_name = array()){
            $current_news_info = new \stdClass();
            foreach($model_name as $k=>$v){
                //未读数量
                $count_name = strtolower($v).'_num';
                $$count_name = request()->param($v)?request()->param($v):'';
                $info_name = strtolower($v).'_info';
                //类（模型）名
                $class_name = 'app\user\model\home\\'.class_basename($v);
                $$info_name = new \stdClass();
                $$info_name->$count_name = $$count_name ? $$count_name : 0;
                $$info_name->info_num = $class_name::where('uid',UID)->order('id desc')->count('id');
                $current_name = strtolower($v);
                $current_news_info->$current_name = $$info_name;
                //显示未读消息的最后一次时间
                $time_name = strtolower($v).'_time';
                $$info_name->$time_name = $class_name::where('uid',UID)->order('id desc')->value('create_time');
            }

            return $current_news_info;
        };

        $this->assign('data',$data($model_name));
        //var_dump($data($model_name));

        return $this->fetch();
    }

    public function show(){
        $user_a = Db::name('recharge')->where('uid', UID)->select();
        foreach ($user_a as $k=>$v){
            if($v['recharge_type'] === 'account'){
                $user_a[$k]['recharge_type'] = '余额消费';
            }
            if($v['recharge_type'] === 'weixin'){
                $user_a[$k]['recharge_type'] = '微信消费';
            }
        }
        $user=array_reverse($user_a);
        Db::name('recharge')->where('uid', UID)->update(['read_status' => '1']);
        $this->assign('user',$user);
        return $this->fetch();
    }

    public function vip(){


        $user_a = Db::name('upgrade_member')->where('uid', UID)->select();
        //处理数据
        foreach ($user_a as $k=>$v){
            if($v['recharge_type'] === 'account'){
                $user_a[$k]['recharge_type'] = '余额消费';
            }
            if($v['recharge_type'] === 'weixin'){
                $user_a[$k]['recharge_type'] = '微信消费';
            }
        }
        Db::name('upgrade_member')->where('uid', UID)->update(['read_status' => '1']);
        $user=array_reverse($user_a);
        $this->assign('user',$user);
        return $this->fetch();

    }

}