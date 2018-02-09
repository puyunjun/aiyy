<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 10:20
 */
namespace app\user\model\home;

use think\Model;
use think\helper\Hash;
use think\Db;
class Login extends Model
{

    protected $table='dp_user_auth';

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }
    //用户登录
    /*
     * @param string $username 用户名
     * @param string $password 登录密码
     * @param string $login_type 登录方式
     * @param string $x  当前经度坐标
     * @param string $y 当前纬度坐标
     * */
    public function login($username = '', $password = '',$x='',$y='',$login_type = '')
    {
        $username = trim($username);
        $password = trim($password);
        $map['status'] = 1;
        //登录方式判断
        if(preg_match("/^1\d{10}$/", $username)){
            //  手机登录，与站内保存密码进行匹配
            $map['identifier']=$username;
            // 查找用户
            $user = $this::get($map);

            if (!$user) {
                return  json(array('status'=>false,'msg'=>'用户不存在或被禁用！'));
            } else {
                //手机验证登录接口
                return json($this->mobile_login($password,$user,$x,$y));
            }
        }elseif($login_type === 'weixin'){
            //接入微信登录认证
            $map['identifier']=$username;
            // 查找用户
            $user = $this::get($map);
            return $this->wx_login($user,$x,$y);
        }elseif($login_type == 'qq'){
            //接入qq登录认证
            return ;
        }





    }


    /**
     * 登录
     * @param object $user 用户登录标识信息
     * @param  array $user_info 用户基本信息
     * @return bool|int
     */
    public function autoLogin($user,$user_info)
    {

        // 记录登录SESSION和COOKIES
        $auth = array(
            'uid'               => $user->uid,
            'identifier'        => $user->identifier,  //用户标识 手机号，微信id或者qq标识
            'nickname'          => $user_info['nickname'],
            'login_time'        => $user_info['login_time'],
            'login_ip'          => get_client_ip(1),
            'head_img'          => $user_info['head_img'],    //用户头像信息
            'is_vip'            => $user_info['is_vip'],
            'login_addr_x'      => $user_info['login_addr_x'],
            'login_addr_y'      => $user_info['login_addr_y'],
        );
        session('user_auth_home', $auth);
        session('user_auth_sign_home', $this->dataAuthSign($auth));

        // 判断是否记住登录，下次自动登录
        /*if ($rememberme) {
            $signin_token = $user->username.$user->id.$user->last_login_time;
            cookie('uid_home', $user->id, 24 * 3600 * 7);
            cookie('signin_token_home', data_auth_sign($signin_token), 24 * 3600 * 7);
        }*/
        return $user->id;  //用户登录标识主键id
    }



    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     * @return string 签名
     */
    public function dataAuthSign($data = [])
    {
        // 数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }

        // 排序
        ksort($data);
        // url编码并生成query字符串
        $code = http_build_query($data);
        // 生成签名
        $sign = sha1($code);
        return $sign;
    }


    /**
     * 判断是否登录
     * @return int 0或用户id
     */
    public function isLogin()
    {
        $user = session('user_auth_home');

        if (empty($user)) {
            return 0;
        }else{
            return session('user_auth_sign_home') == $this->dataAuthSign($user) ? $user['uid'] : 0;
        }
    }



    //手机登录验证方法
    /*
     * @param string $password  用户输入的密码
     * @param  object $user 站内用户信息对象
     * @param string $x  当前经度坐标
     * @param string $y 当前纬度坐标
     * */
    public function mobile_login($password = '',$user,$x = '',$y = ''){
        if (!Hash::check((string)$password, $user->credential)) {
            return  json(array('status'=>false,'msg'=>'密码错误！'));
        } else {

            $uid = $user->uid;
            //最新登录信息
            $last_info_arr= [
                'login_time' => request()->time(),
                'login_ip' => get_client_ip(1),
                'login_addr_x' => $x,
                'login_addr_y' => $y
            ];
            if (Db::name('user')->data($last_info_arr)->where('id',$uid)->update()) {
                // 保存成功进入登录页面
                return $this->autoLogin($this::get(['uid'=>$uid]),Db::name('user')->where('id',$uid)->find());
            } else {
                // 更新登录信息失败
                $this->error = '登录信息更新失败，请重新登录！';
                return  array('status'=>false,'msg'=> $this->error);
            }
        }
    }


    /*微信登录验证方法
     * @param  object $user 站内用户信息对象
     * @param string $x  当前经度坐标
     * @param string $y 当前纬度坐标
     * */

    public function wx_login($user,$x='',$y=''){
        $uid = $user->uid;
        //最新登录信息
        $last_info_arr= [
            'login_time' => request()->time(),
            'login_ip' => get_client_ip(1),
            'login_addr_x' => $x,
            'login_addr_y' => $y
        ];
        if (Db::name('user')->data($last_info_arr)->where('id',$uid)->update() !== false) {
            // 保存成功进入登录页面
            return $this->autoLogin($this::get(['uid'=>$uid]),Db::name('user')->where('id',$uid)->find());
        } else {
            // 更新登录信息失败 $this->error = '登录信息更新失败，请重新登录！';
            return false;
        }
    }
}