<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 11:16
 */

namespace app\user\model\home;
use think\Db;
use think\Model;
use think\helper\Hash;

class Sign  extends Model
{

    protected $name = 'user';

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    //检测手机用户是否已经注册
    public function check_phone($mobile_phone){

        return DB::name('user_auth')->where('identifier',$mobile_phone)->field('id,uid')->find();
    }

    //添加用户基础信息
    public function add_user_base($data = array()){

        $re  =   $this->insert($data);

        return $this->getLastInsID();

    }

    //添加用户认证信息表

    public function add_user_auth($data = array()){
        $user_auth_info['identifier']=$data['identifier'];
        //密码加密
        $user_auth_info['credential']=$this->setPasswordAttr($data['credential']);
        //注册方式
        $user_auth_info['identity_type']= $data['identity_type'];
        $user_auth_info['status']=$data['status'];
        $user_auth_info['uid'] = $data['uid'];
        $user_auth_info['regip'] = $data['regip'];
        $user_auth_info['create_time'] = request()->time();
        return Db::name('user_auth')->insert($user_auth_info);
    }


    //用户修改密码
    /*
     * @param string $password  用户更新密码
     * @param array    $id_arr        用户id，uid数组
     * */
    public function updatePass($password,$id_arr = array()){
        //密码进行hash加密
        $user_auth_info['credential'] = $this->setPasswordAttr($password);
        //信息更新时间
        $user_auth_info['create_time'] = request()->time();

        //时间
        $user_base_info['login_time'] = request()->time();
        //ip地址
        $user_base_info['login_ip'] = get_client_ip(1);
        //更新入库并跟新登录基础信息
        $res1 = '';
        $res2 = '';
        if($id_arr){
            $id = $id_arr['id'];
            $uid = $id_arr['uid'];

            //更新用户认证表
            $res1 =  Db::name('user_auth')->where('id',$id)->update($user_auth_info);

            //更新用户基础信息表
            $res2 =  $this->where('id',$uid)->update($user_base_info);
        }
        if($res1 && $res2){
            return true;
        }
    }

    /*生成约游id号
     * return int 约游id号
     * */
    private function make_sys_id(){
        return mt_rand(10000,100000);
    }

}