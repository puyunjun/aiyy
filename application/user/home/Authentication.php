<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\16 0016
 * Time: 18:45
 */
/*
 * 个人认证控制器
 * */
namespace app\user\home;


class Authentication extends Common
{

    public function index(){
        return $this->fetch();
    }

}