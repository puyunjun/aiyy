<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\16 0016
 * Time: 19:17
 */

namespace app\user\home;
use app\index\controller\Aliyun;
use think\Session;
use app\user\model\home\Sign as SignInModel;

use think\Db;
use app\user\model\home\Login;
use think\Url;


class BecomeEscort extends Common
{

    public static $model;

    private static $Aliyun;

    public $login_model;
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        self::$model = new SignInModel;
        self::$Aliyun = Aliyun::getinstance();
        $this->login_model = new Login();
        $this->assign('url_root', config('root_url'));

    }
    public function ajax(){

        if ($this->request->isAjax()) {

            //对比验证码  ,验证码从session里面取
            //模拟session 验证码
            $verify = request()->post('verify_code');
            if (request()->session('verify_code') !== $verify) {

                return json(array('status' => false, 'msg' => '验证码不正确'));
            }else{
                Session::delete('verify_code');
                Db::name('user')->where('id',UID)->update(['is_escort' => 1]);
                return json(array('status' => true, 'msg' => '签约成功'));
            }
            return json(array('status' => false, 'msg' => '服务器繁忙，稍后再试'));

        }
    }
    public function index(){

        $data= Db::name('user')->where('id',UID)->find();
        $type= Db::name('user_identity')->where('uid',UID)->find();
        $this->assign("data", $data);
        $this->assign("type", $type);
        $forgetPass = request()->param('forget') ? intval(1) : '';
        $this->assign('forgetPass', $forgetPass);
        return $this->fetch();
    }
    public function get_verify($mobile_phone = '')
    {
        $id=UID;
        $name= Db::name('user')->where("id=$id AND is_escort=1")->select();
        if ($name) {
            return json(array('code' => 300, 'msg' => '您已成为伴游,请勿重复注册'));
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


}