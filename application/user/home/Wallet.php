<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\18 0018
 * Time: 10:52
 */

namespace app\user\home;

use think\Controller;
class Wallet extends Controller
{


    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

    }

    public function index(){

        return $this->fetch();

    }


}