<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\8 0008
 * Time: 17:18
 */

namespace app\index\controller;
require_once VENDOR_PATH.'wx_pay/lib/WxPay.Api.php';
require_once VENDOR_PATH.'wx_pay/example/WxPay.JsApiPay.php';

use app\admin\model\Config;
use think\Controller;
use think\Db;
class Wxpay extends Home
{

    public $jsApiParameters;
    public $editAddress;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $data = $this->pay_money();
        $this->jsApiParameters = $data['jsApiParameters'];
        $this->editAddress = $data['editAddress'];
    }

    public function pay_money() {
       /* $a = new \JsApiPay();
        var_dump($a->GetOpenid());*/

        //①、获取用户openid
        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://teacherpu.top/index/wxpay/notify");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        var_dump($input);
//获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        return array('jsApiParameters'=>$jsApiParameters,'editAddress'=>$editAddress);
    }


    public function index(){
        $this->assign('jsApiParameters',$this->jsApiParameters);
        $this->assign('editAddress',$this->editAddress);
        return $this->fetch();
    }


    public function notify(){
        $wxData = file_get_contents("php://input");

        Db::name('user')->where('id',5)->update(array('nickname'=>'aaaa'));
        //file_put_contents('/tmp/2.txt',$wxData,FILE_APPEND);
        try{
            $resultObj = new \WxPayResults();
            $wxData = $resultObj->Init($wxData);
        }catch (\Exception $e){
            $resultObj ->setData('return_code','FAIL');
            $resultObj ->setData('return_msg',$e->getMessage());
            return $resultObj->toXml();
        }

        if ($wxData['return_code']==='FAIL'||
            $wxData['return_code']!== 'SUCCESS'){
            $resultObj ->setData('return_code','FAIL');
            $resultObj ->setData('return_msg','error');
            return $resultObj->toXml();
        }
        //TODO 根据订单号 out_trade_no 来查询订单数据
        $out_trade_no = $wxData['out_trade_no'];
        //此处为举例

        //TODO 数据更新 业务逻辑处理 $order


    }

}