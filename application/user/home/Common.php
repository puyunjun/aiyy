<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 10:05
 */

namespace app\user\home;

use app\index\controller\Home;
/*
 * 前台公共控制器
 *
 * */

class Common extends Home
{

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        // 判断是否登录，并定义用户ID常量
        defined('UID') or define('UID', $this->isLogin());

    }


    /**
     * 检查是否登录，没有登录则跳转到登录页面
     * @return int
     */
    final protected function isLogin()
    {
        // 判断是否登录
        if ($uid = is_member_signin()) {
            // 已登录
            return $uid;
        } else {
            // 未登录
            $this->redirect('user/Login/index');
        }
    }

}