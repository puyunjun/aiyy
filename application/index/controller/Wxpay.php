<?php

namespace app\index\controller;

use think\Controller;
use wxpay\database\WxPayResults;
use wxpay\database\WxPayUnifiedOrder;
//use wxpay\NativePay; 扫码支付
use wxpay\WxPayApi;
use wxpay\WxPayConfig;
use wxpay\JsApiPay;
use think\Db;
class Wxpay  extends Controller
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

    }

    /**
     * 微信支付 回调逻辑处理
     * @return string
     */
    public function notify(){

        $wxData = file_get_contents("php://input");

        //file_put_contents('/tmp/2.txt',$wxData,FILE_APPEND);
        try{
            $resultObj = new WxPayResults();
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
        $attach = $wxData['attach'];   //附带升级会员组id  以及会员id
        //此处为举例
        //升级会员等级业务更新
        $attach_info = json_decode($attach);
        $group_id = $attach_info->group_id;
        $uid = $attach_info->uid;
        $re = Db::name('user')->where('id',"$uid")->update(array('group_id'=>"$group_id"));
        /*if (!$order || $order->pay_status == 1){
            $resultObj ->setData('return_code','SUCCESS');
            $resultObj ->setData('return_msg','OK');
            return $resultObj->toXml();
        }*/
        //TODO 数据更新 业务逻辑处理 $order
    }

        /*
         *
         * 接受支付参数
         * @param int $type   付费项目，如充值或者升级会员
         * */
    public function index(){
        //获取订单信息  会员升级或者其他订单信息
        $member_fee_info = $this->get_become_member_data()['upgrade_member'];
        //获取用户id

        $member_fee_info['uid'] = $this->get_become_member_data()['uid'];
        //①、获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->getOpenid($member_fee_info)['openid'];  //获取微信openid
        $order_info = json_decode(request()->param('state'));
        $body_info = $order_info->body_info;    //交易信息商品名
        $attach  = json_encode(
            array(
                'group_id'=>$order_info->group_id,
            'uid'=>$order_info->uid)
        );   //附带信息

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($body_info);
        $input->SetAttach($attach);
        $input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotalfee("1");           //测试阶段写为1
        $input->SetTimestart(date("YmdHis"));
        $input->SetTimeexpire(date("YmdHis", time() + 600));
        $input->SetGoodstag("test");
        $input->setNotifyUrl('http://teacherpu.top/index/wxpay/notify');
        $input->SetTradetype("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        $this->assign('jsApiParameters',$jsApiParameters);
        $this->assign('editAddress',$editAddress);
        return $this->fetch();
    }

    //升级会员接收方法

    public function get_become_member_data(){
        //获取选择的会员组id
        //会员升级订单信息  若为其他支付信息则另外获取信息.
        $group_id = intval(request()->post('kt'));
        $uid = intval(request()->post('get_uid'));
        //开通,默认开通月费
        $price_type = 'price_y';

        $map = [
            'id'=>$group_id,
        ];
        //获取该会员组所需的费用

        $member_fee_info = Db::name('user_group')->where($map)->field("$price_type,group_name")->find();

        return $data =[
            'upgrade_member'=>[
                'money'=>$member_fee_info[$price_type],
                'group_id'=>$group_id,
                'body_info'=>"升级".$member_fee_info['group_name']
            ],
            'uid'=>$uid,

        ];
        //调用支付方法

        //$this->pay_money($member_fee_info[$price_type],$group_id,"升级".$member_fee_info['group_name']);

        //$this->index($member_fee_info[$price_type],$group_id,"升级".$member_fee_info['group_name']);
    }

}
