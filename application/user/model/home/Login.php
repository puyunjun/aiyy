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
     * */
    public function login($username = '', $password = '',$login_type = '')
    {
        $username = trim($username);
        $password = trim($password);

        //登录方式判断
        if(preg_match("/^1\d{10}$/", $username)){
            //  手机登录，与站内保存密码进行匹配
            $map['identifier']=$username;
        }elseif($login_type == 'weixin'){
            //接入微信登录认证
            return ;
        }elseif($login_type == 'qq'){
            //接入qq登录认证
            return ;
        }

        $map['status'] = 1;

        // 查找用户
        $user = $this::get($map);

        if (!$user) {
            return  json(array('status'=>false,'msg'=>'用户不存在或被禁用！'));
        } else {
            if (!Hash::check((string)$password, $user->credential)) {
                return  json(array('status'=>false,'msg'=>'密码错误！'));
            } else {

                $uid = $user['uid'];
                //最新登录信息
                $last_info_arr= [
                    'login_time' => request()->time(),
                    'login_ip' => get_client_ip(1)
                ];
                if (Db::name('user')->data($last_info_arr)->where('id',$uid)->update()) {
                    // 保存成功进入登录页面
                    return $this->autoLogin($this::get(['uid'=>$uid]),Db::name('user')->where('id',$uid)->find());
                } else {
                    // 更新登录信息失败
                    $this->error = '登录信息更新失败，请重新登录！';
                    return  json(array('status'=>false,'msg'=> $this->error));
                }
            }
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
            'is_vip'            => $user_info['is_vip']
        );
        session('user_auth_home', $auth);
        session('user_auth_sign_home', $this->dataAuthSign($auth));

        // 保存用户节点权限

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


}