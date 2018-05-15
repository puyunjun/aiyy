<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\4\24 0024
 * Time: 16:01
 */

namespace app\broker\home;

use think\Controller;
use think\Db;
use app\index\controller\Aliyun;
use think\Session;
class Signin extends Controller
{

    /*
     * 经纪人注册控制器
     * */

    public function index(){
        return $this->fetch();
    }

    public function check(){

        $data = $this->get_data();
        if(intval(request()->post('verify_code')) !== session('broker_verify_code')){
            return json(array('code'=>301,'msg'=>'验证码错误'));
        }
        $validate = $this->validate($data, 'Signin');
        if ($validate === true) {
            //($data);exit;
            Db::startTrans();
            try{
               $ubid = Db::name('user_broker_auth')->insertGetId($data);  //返回新增经纪人id
                //修改经纪人邀请码
                $invite_code = $this->make_uuid($ubid);
                Db::name('user_broker_auth')->where('id',$ubid)->setField('invite_code',$invite_code);
                //添加经纪人初始信息id
                $re = Db::name('user_broker')->insert(array('ubid'=>$ubid));
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if($ubid && $re){
                session('broker_verify_code',null);
                return json(array('code'=>200,'msg'=>'注册成功'));
            }else{
                return json(array('msg'=>'稍后再试'));
            }
        }else{
            return json(array('code'=>201,'msg'=>$validate));
        }

    }

    private function get_data(){
        $data = [];
        $data['phone'] = request()->post('mobile_phone');
        $data['password'] = md5(request()->post('password'));
        $data['create_ip'] = get_client_ip(1);
        $data['create_time'] = request()->time();
        return $data;
    }


    /*
     * 获取手机验证码
     * */

    public function get_code($mobile_phone=''){
        if(!$this->is_signin($mobile_phone)){
            //若已经注册
            return json(array('code'=>404,'msg'=>'该手机已经注册'));
        }
        $Aliyun = Aliyun::getinstance();
        $code = $Aliyun->generate_code();

        $response = $Aliyun->sendSms(
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
            Session::set('broker_verify_code', $code);
            return json(array('code' => 200, 'msg' => '发送成功'));
        }
    }


    //忘记密码获取验证码
    public function get_forget_code($mobile_phone=''){
        if($this->is_signin($mobile_phone)){
            return json(array('code'=>404,'msg'=>'该手机未注册,请直接注册'));
        }
        $Aliyun = Aliyun::getinstance();
        $code = $Aliyun->generate_code();

        $response = $Aliyun->sendSms(
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
            Session::set('broker_verify_code_forg', $code);
            return json(array('code' => 200, 'msg' => '发送成功'));
        }
    }



    /*
     * 注册时判断手机号是否已经被注册
     * */

    public function is_signin($mobile_phone = ''){
        $phone = Db::name('user_broker_auth')->where('phone',$mobile_phone)->value('id');
        if($phone){
            return false;
        }else{
            return true;
        }
    }
    /**
     * 经纪人生成初始邀请码  UUID
     * @param      int   $id  新注册的经纪人id
     * @param      string  an optional prefix
     * @return     string  the formatted uuid
     */
    public function make_uuid($id = 0,$prefix = '')
    {
        $uuid = strtoupper(uniqid($id));
        return $prefix . $uuid;
    }


    //忘记密码页面
    public function forgetp(){
        return $this->fetch();
    }


    //修改密码

    public function update_ps(){
        if(intval(request()->post('verify_code')) !== session('broker_verify_code_forg')){

            return json(array('code'=>301,'msg'=>'验证码错误'));
        }
        $password = md5(request()->post('password'));

        $phone = request()->post('mobile_phone');

        Db::name('user_broker_auth')->where('phone',$phone)->update(array('password'=>$password));

        return json(array('code'=>200,'msg'=>'修改成功'));
    }
}