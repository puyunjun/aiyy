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
use wxpay\JsApiPay;
use think\Db;
use app\user\model\home\Login;
class Signin extends Controller
{

    public static $model;

    private static $Aliyun;

    public $login_model;
    public function _initialize()
    {
        self::$model = new SignInModel;
        self::$Aliyun = Aliyun::getinstance();
        $this->login_model = new Login();
        $this->assign('url_root', config('root_url'));

    }

    /*
     * 注册首页  以手机注册为主
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


    //初始化数据  用户使用手机注册

    private function get_data()
    {

        $data = [];

        /*会员个人相关信息*/
        $data['user'] = array(
            'phone' => strip_tags(request()->post('username')),       /*手机号*/
            'sex' => intval(request()->post('sex')),
            'login_time' => request()->time(),
            'login_ip' => get_client_ip(1),
            'login_addr_x' => '',
            'login_addr_y' => '',
            'is_vip' => 4,
            'sys_id' => mt_rand(10000, 100000),         /*系统分配的初始约游id*/
        );

        /*会员认证信息*/
        $data['user_auth'] = array(
            'identifier' => strip_tags(request()->post('username')),
            'credential' => strip_tags(request()->post('password')),       /*用户凭证，手机号保存密码,用model层hash加密*/
            'identity_type' => 'mobile',  /*会员注册默认使用手机号注册*/
            'status' => 1,             /*会员起始状态，1=>正常*/
            'regip' => get_client_ip(1),
            'update_time'=> ''
        );
        return $data;
    }


    //第三方注册

    public function third_party_sign(){
        //微信登录授权
        $wx = new JsApiPay();

        //获取openid相爱信息数组
        $openId_arr = $wx->getOpenid(array('sex'=>0));  //初始为未知性别  绑定手机号的时候设置性别并无法更改
        $openId  = isset($openId_arr['openid']) ? $openId_arr['openid'] : '';
        //获取临时access_token
        $access_token  = isset($openId_arr['access_token']) ? $openId_arr['access_token'] : '';
        //过期时间
        $exp_time = isset($openId_arr['expires_in']) ? $openId_arr['expires_in']: '';
        $state = json_decode(request()->param('state'));
        $sex = $state->sex;

        if(!$openId){
            //获取到openid
                echo "<script>alert('微信服务器繁忙')</script>";
                $this->redirect('user/Signin/index');
        }else{
            //判断是否用微信登录，若登陆过直接进入个人页面或者首页

            $map = [
                'identity_type' => 'weixin',
                'identifier' => $openId,
            ];
            if(!$uid = Db::name('user_auth')->where($map)->value('uid')){
                //用户uid不存在，进行注册添加
                //整合用户基础信息
                $data = $this->get_wei_data($openId,$access_token,1);
                $user_base_info = $data['user'];

                //添加用户基础信息
                $newUid = self::$model->add_user_base($user_base_info);
                //用户约游id在用户绑定手机号的时候进行分配,用户未绑定则不分配
                //self::$model->where('id', $newUid)->setField('sys_id', $user_base_info['sys_id'] . $newUid);
                // var_dump($user_base_info);  exit;
                if ($newUid) {
                    //返回用户注册新增主键id,在添加用户认证信息表
                    $data['user_auth']['uid'] = $newUid;

                    $authId = self::$model->add_user_auth($data['user_auth']);
                    if ($authId) {
                        //微信第三方注册成功  进入自动登录

                        if( $re =$this->login_model->login($openId,'','','','weixin')){
                            $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/index/Index/index',302);
                        }else{
                            echo "<script>alert('服务器繁忙，稍后再试')</script>";$this->redirect('user/Signin/index');
                        }
                         //$this->redirect('http://'.$_SERVER['HTTP_HOST'].'/user/Login/index',['login_type'=>'weixin','username' => $openId],302);
                    } else echo "<script>alert('服务器繁忙，稍后再试')</script>";$this->redirect('user/Signin/index');

                }
            }
        }
    }

    /*
     * 获取使用微信注册用户信息  return arr $data
     * @param string $openid            用户openid，唯一标识
     * @param string $access_token      微信平台机器access_token临时凭证
     * @param int $sex  用户选择的性别
     * */
    private  function get_wei_data($openid,$access_token = '',$sex){
        $data = [];

        /*会员个人相关信息*/
        $data['user'] = array(
            'phone' => $openid,       /*用户未绑定手机的时候即为微信openid*/
            'sex' => $sex,
            'login_time' => request()->time(),
            'login_ip' => get_client_ip(1),
            'login_addr_x' => '',
            'login_addr_y' => '',
            'is_vip' => 4,                  //起始为非vip用户
            //'sys_id' => mt_rand(10000, 100000),         /*系统分配的初始约游id 微信注册暂时不分配约游id*/
        );

        /*会员认证信息*/
        $data['user_auth'] = array(
            'identifier' => $openid,
            'credential' => $access_token,       /*用户凭证，手机号保存密码,用model层hash加密*/
            'identity_type' => 'weixin',  /*会员注册默认使用手机号注册*/
            'status' => 1,             /*会员起始状态，1=>正常*/
            'regip' => get_client_ip(1),
            'update_time'=> ''
        );
        return $data;
    }
}