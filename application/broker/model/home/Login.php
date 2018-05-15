<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\4\25 0025
 * Time: 11:49
 */

namespace app\broker\model\home;
use think\Model;
use think\Db;
class Login extends Model
{

    protected $name = 'user_broker_auth';

    /*
     * 验证登录
     * @param array $data 登录数据
     *
     * */
    public function login($data = array()){
        $map = $data;

        $re = $this::get($map);

        if(!$re){
            return json(array('code'=>201,'msg'=>'帐号密码不匹配'));
        }else{
           $res =  Db::name('user_broker')->where('ubid',$re->id)->field('id,ubid,status')->find();
            if($res['status'] === 0){
                return json(array('code'=>404,'msg'=>'账户号异常，被禁封'));
            }else{
                //修改用户最新登录时间
                Db::name('user_broker')
                    ->where('ubid',$re->id)
                    ->update(array('last_login_time'=>request()->time(),'last_login_ip'=>get_client_ip(1)));
                //记录登录信息
               $ubid = $this->login_session($this->login_info($re->id));
               if($ubid){
                   return json(array('code'=>200,'msg'=>'登录成功，正在跳转'));
               }
            }
        }
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

    /*
     * 记录登录session
     * @param array  $user_info 用户登录基本信息,包括认证信息
     * */
    public function login_session($user_info = array()){
        $login_info =array(
            'phone'=>$user_info['phone'],
            'password'=>$user_info['password'],
            'ubid'=>$user_info['ubid'],
            'username'=>$user_info['username'],
            'last_login_time'=>$user_info['last_login_time'],
        );

        session('broker_user_auth_home', $login_info);
        session('broker_user_auth_sign_home', $this->dataAuthSign($login_info));
        //记录cookie 有效期内可自动登录
        if(!cookie('broker_uid_home')){
            $sign_token_str = $user_info['phone'].$user_info['ubid'].$user_info['last_login_time'];
            cookie('broker_uid_home', $user_info['ubid'], 24 * 3600 * 7);
            cookie('broker_signin_token_home', data_auth_sign($sign_token_str), 24 * 3600 * 7);
        }
        return $user_info['ubid'];
    }


    /*
     * 自动登录
     * */
    public function auto_login(){
        $user = session('broker_user_auth_home');

        if (empty($user)) {
            // 判断是否记住登录
            if (cookie('?broker_uid_home') && cookie('?broker_signin_token_home')) {
                $user_info = $this->login_info();
                if ($user_info) {
                    $signin_token = data_auth_sign($user_info['phone'].$user_info['ubid'].$user_info['last_login_time']);
                    if (cookie('broker_signin_token_home') == $signin_token) {
                        // 自动登录
                        $this->login_session($user_info);
                        return $user_info['ubid'];
                    }
                }
            };
            return 0;
        }else{
            return session('broker_user_auth_sign_home') == data_auth_sign($user) ? $user['ubid'] : 0;
        }
    }

    /*
     * 获取用户登录信息
     * */

    public function login_info($ubid = ''){
        if(!$ubid){
            $ubid = cookie('broker_uid_home');
        }
        $re = Db::name('user_broker')
                ->alias('ub')
                ->join('user_broker_auth uba','uba.id = ub.ubid')
                ->where('ub.ubid',$ubid)
                ->field('uba.phone,uba.password,ub.ubid,ub.username,ub.last_login_time')
                ->find();
        return $re;
    }
}