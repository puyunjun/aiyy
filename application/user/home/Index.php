<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 9:50
 */
namespace app\user\home;
use think\Controller;
use app\user\model\home\User As UserModel;
use think\Session;

class Index extends Common
{
    public $model;
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //继承公共基础类判断是否登录
        $this->model = new UserModel();
    }

    public function index(){
        /*用户首页方法*/
        $this->assign('user_base_info',$this->model->user_base_info());

        //var_dump(UserModel::user_privilege());exit;



        return  $this->fetch();
    }

    public function allow_privilege(){
        /*判断会员截止时间是否到期*/
        if(!Session::get('user_auth_home')){
            return json(array('status'=>false,'msg'=>'请登录'));
        }

        $user_info = UserModel::user_privilege();

            //判断会员是否有权限查看
            if($user_info->allow_priview_photo == 0){
                  $data = array('status'=>false,'msg'=>'无法查看，权限太低，请升级会员或者完善资料');
                  return json($data);
            }
            //判断会员是否到期
            if($user_info->member_deadline < time()){
                return json(array('status'=>false,'msg'=>'会员已到期，无法查看'));
            }

    }
}