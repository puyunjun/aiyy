<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 11:15
 */

namespace app\user\home;

use app\index\controller\Aliyun;
use think\Controller;
use think\Session;
use app\user\model\home\Sign as SignInModel;


class Signin extends Controller
{

    public static $model;

    private static $Aliyun;

    public function _initialize()
    {
        self::$model = new SignInModel;
        self::$Aliyun = Aliyun::getinstance();

        $this->assign('url_root', config('root_url'));

    }

    /*
     * 注册首页
     * */
    public function index()
    {
        //判断是否进入忘记密码修改页面
        $forgetPass = request()->param('forget') ? intval(1) : '';
        $this->assign('forgetPass', $forgetPass);

        if ($this->request->isAjax()) {

            //对比验证码  ,验证码从session里面取
            //模拟session 验证码
            $verify = request()->post('verify_code');
            if (request()->session('verify_code') !== $verify) {
                //验证码不正确
                return json(array('status' => false, 'msg' => '验证码不正确'));
            }

            //验证码正确判断若为修改密码,检测得到帐号id,利用id修改其新输入的密码
            $res = self::$model->updatePass(request()->post('password'), self::$model->check_phone(request()->post('username')));

            if ($res) {
                return json(array('code' => 202, 'msg' => '密码修改成功'));
            }
            //验证码正确，加入数据数据库
            //整合用户基础信息
            $data = $this->get_data();
            $user_base_info = $data['user'];

            //添加用户基础信息
            $newUid = self::$model->add_user_base($user_base_info);
            //为用户分配约游id
            self::$model->where('id', $newUid)->setField('sys_id', $user_base_info['sys_id'] . $newUid);
            // var_dump($user_base_info);  exit;
            if ($newUid) {
                //返回用户注册新增主键id,在添加用户认证信息表
                $data['user_auth']['uid'] = $newUid;

                $authId = self::$model->add_user_auth($data['user_auth']);
                if ($authId) {
                    Session::delete('verify_code');
                    return json(array('status' => true, 'msg' => '注册成功'));
                } else return json(array('status' => false, 'msg' => '服务器繁忙，稍后再试'));

            }

        }
        return $this->fetch();

    }


    /*短信接口函数
     * @param  string $mobile_phone 用户电话号码
     * */
    public function get_verify($mobile_phone = '')
    {
        //检测当前手机用户正在注册，但已经注册， 并且不是修改密码状态

        if (self::$model->check_phone($mobile_phone) && !request()->post('forget')) {
            return json(array('code' => 300, 'msg' => '该手机号已被注册,请勿重复注册'));
        }
        //若为修改密码，并且没有查询到有改手机号码注册记录则提示用户注册
        if (!self::$model->check_phone($mobile_phone) && request()->post('forget')) {
            return json(array('code' => 201, 'msg' => '该手机号并未注册，请直接注册'));
        }
        //调用短信接口  发送验证码，返回发送成功状态码
        //模拟session  验证码
        $code = self::$Aliyun->generate_code();

        $response = self::$Aliyun->sendSms(
            "爱约游", // 短信签名
            "SMS_117521361", // 短信模板编号
            $mobile_phone, // 短信接收者
            Array(  // 短信模板中字段的值
                "code" => $code,
            ),
            time()   // 流水号,选填
        );

        if ($response->Code == 'OK') {
            //设置session
            Session::set('verify_code', $code);
            return json(array('code' => 200, 'msg' => '发送成功'));
        }
    }

    //初始化数据

    private function get_data()
    {

        $data = [];

        /*会员个人相关信息*/
        $data['user'] = array(
            'phone' => strip_tags(request()->post('username')),       /*手机号*/
            'sex' => intval(request()->post('sex')),
            'login_time' => request()->time(),
            'login_ip' => get_client_ip(1),
            'login_addr_x' => '当前经纬度x坐标',
            'login_addr_y' => '当前经纬度y坐标',
            'is_vip' => 4,
            'occupation_id' => '0',                  /*职业id*/
            'sys_id' => mt_rand(10000, 100000),         /*系统分配的初始约游id*/
        );

        /*会员认证信息*/
        $data['user_auth'] = array(
            'identifier' => strip_tags(request()->post('username')),
            'credential' => strip_tags(request()->post('password')),       /*用户凭证，手机号保存密码,用model层hash加密*/
            'identity_type' => 'mobile',  /*会员注册默认使用手机号注册*/
            'status' => 1,             /*会员起始状态，1=>正常*/
            'regip' => get_client_ip(1)
        );
        return $data;
    }
}