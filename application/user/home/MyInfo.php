<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\18 0018
 * Time: 10:21
 */

namespace app\user\home;

use think\Controller;
class MyInfo extends Controller
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index(){

        return $this->fetch();

    }


}