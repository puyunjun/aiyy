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
                $count_name = strtolower($v).'_num';
                $$count_name = request()->param($v)?request()->param($v):'';
                $info_name = strtolower($v).'_info';
                $class_name = 'app\user\model\home\\'.class_basename($v);
                $$info_name = new \stdClass();
                $$info_name->$count_name = $$count_name ? $$count_name : 0;
                $$info_name->info_num = $class_name::where('uid',UID)->order('id desc')->count('id');
                $current_name = strtolower($v);
                $current_news_info->$current_name = $$info_name;
            }
            return $current_news_info;
        };

        $this->assign('data',$data($model_name));
        //var_dump($data($model_name));

        return $this->fetch();
    }
}