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
class Index extends Controller
{
    public $model;
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //继承公共基础类判断是否登录
        $this->model = new UserModel();
    }

    public function index(){

        $this->assign('user_base_info',$this->model->user_base_info());
        return  $this->fetch();

    }
}