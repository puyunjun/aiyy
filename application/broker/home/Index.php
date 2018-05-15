<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\4\24 0024
 * Time: 15:56
 */

namespace app\broker\home;

use  app\index\controller\Wxapi;
use app\broker\model\home\BrokerUser;
use think\Db;
class Index extends Common
{
    /*
     * 经济人控制器
     * */
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //继承公共基础类判断是否登录
    }


    //经纪人个人首页
    public function index(){

        //获取经纪人id
        $ubid = UBID;
        //获取二维码图片
        $user_code_img = BrokerUser::where('ubid',UBID)->value('code_img');
        if(trim($user_code_img)){
            $this->assign('code_img',$user_code_img);
        }else{
            $code_img = $this->make_code_img($ubid);
            BrokerUser::where('ubid',UBID)->setField('code_img',$code_img);
            $this->assign('code_img',$code_img);
        }
        $this->assign('code_img_aiyoo','img/aiyoo.png');
        //查询推广邀请码
        $invite_code = Db::name('user_broker_auth')->where('id',UBID)->value('invite_code');
        $this->assign('invite_code',$invite_code);
        $share = new Share('wx1800872e18acc8f7','03b564744dffd2cc239250437ee139db');

        $signPackage = $share->getSignPackage();

        $url = 'http://m.aiyueyoo.com/index/index/index/invite_code/'.UBID.'.html';
        $this->assign('signPackage',$signPackage);
        $this->assign('url',$url);
        return $this->fetch();
    }


    //生成个人推广二维码
    /*
     * $param int $ubid 经纪人id
     * */
    public function make_code_img($ubid = 0){
        $wx_tool = new Wxapi();
        $token = $wx_tool->get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
        $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$ubid.'}}}';
        $a = $wx_tool->https_request($url,$data);
        $ti = json_decode($a,true)['ticket'];
        $ticket_img = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.UrlEncode($ti);
        return $ticket_img;
    }
}