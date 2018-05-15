<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\4 0004
 * Time: 16:34
 */

namespace app\index\controller;
use think\Request;
define("TOKEN", "aiyueyou"); //TOKEN值
class Wxapi
{
    //验证token
    public function valid() {
        $echoStr = isset($_GET["echostr"])?$_GET["echostr"]:'';
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }else{
            //平时的消息推送处理
            $this->responseMsg();
        }
    }
    private function checkSignature() {
        $signature = isset($_GET["signature"])?$_GET["signature"]:'';
        $timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:'';
        $nonce =isset($_GET["nonce"])? $_GET["nonce"] : '';
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ) {
            return true;
        } else {
            return false;
        }
    }


    //获取access_token
    public function get_access_token(){
        $appid = "wx1800872e18acc8f7";
        $appsecret = "03b564744dffd2cc239250437ee139db";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        $access_token = $jsoninfo["access_token"];

        return $access_token;
    }


function make_menu(){
    $jsonmenu = '{
     "button":[
     {    
          "type":"view",
          "name":"爱约游",
          "url":"http://m.aiyueyoo.com/index/index/index"
      },
      {
           "type":"view",
           "name":"会员充值",
           "url":"http://m.aiyueyoo.com/index/index/index"
      },
      {
           "type":"view",
           "name":"会员注册",
           "url":"http://m.aiyueyoo.com/user/signin/index"
      }
      ';

    $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->get_access_token();
    $result = $this->https_request($url, $jsonmenu);
    var_dump($result);
}


   public function https_request($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }



    public function  get_code_openid(){

        //获取code
        $code = $_GET["code"];
        $appid = "wx1800872e18acc8f7";
        $secret = "03b564744dffd2cc239250437ee139db";


        //第一步:取全局access_token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        $token = $this->getJson($url);

        //第二步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2 = $this->getJson($oauth2Url);

        //第三步:根据全局access_token和openid查询用户信息
        $access_token = $token["access_token"];
        $openid = $oauth2['openid'];

        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo = $this->getJson($get_user_info_url);
        var_dump($userinfo);
     }


     //网页授权，公众号外部，没有关注的时候也可以获取信息
     public function get_new_info(){
         //获取code
         $code = $_GET["code"];
         $appid = "wx1800872e18acc8f7";
         $secret = "03b564744dffd2cc239250437ee139db";


         //第一步:取全局access_token
         $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
         $token = $this->getJson($url);

         //第三步:根据全局access_token和openid查询用户信息
         $access_token = $token["access_token"];
         $openid = $token['openid'];

         $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
         $userinfo = $this->getJson($get_user_info_url);
         return $userinfo;
     }
    public function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    public function get_code($order_info = array()){
        if(!isset($_GET['code'])){

        //$scope='snsapi_userinfo';//需要授权
        $appid='wx1800872e18acc8f7';
        $redirect_uri =  urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
        $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        $state = json_encode($order_info);
        $url = $order_info ? str_replace("STATE", $state, $url) : $url;
        header("Location:".$url);
        exit;
        }else{
            $code = $_GET["code"];
            $appid = "wx1800872e18acc8f7";
            $secret = "03b564744dffd2cc239250437ee139db";

            //第一步:取全局access_token
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $token = $this->getJson($url);
            //第三步:根据全局access_token和openid查询用户信息
            $access_token = $token["access_token"];
            $openid = $token['openid'];

            $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
            $userinfo = $this->getJson($get_user_info_url);
            $userinfo['token']['access_token'] = $access_token;
            return $userinfo;
        }


    }
    public function get_that(){
        var_dump($this->get_code());
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data


            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";

               /* "<xml>
                    <ToUserName>< ![CDATA[%s] ]></ToUserName> 
                    <FromUserName>< ![CDATA[%s] ]></FromUserName> 
                    <CreateTime>%s</CreateTime> 
                    <MsgType>< ![CDATA[%s] ]></MsgType> 
                    <Event>< ![CDATA[%s] ]></Event> 
                    <EventKey>< ![CDATA[%s] ]></EventKey> 
                    <Ticket>< ![CDATA[%s] ]></Ticket> 
                </xml>";*/
            $Ticket =   isset($postObj->Ticket)?$postObj->Ticket:'';
            $EventKey = trim((string)$postObj->EventKey);
            $keyArray = explode("_", $EventKey);
            if($Ticket){
                if (count($keyArray) == 1){
                    //已关注者扫描
                    $msgType = "text";
                    $contentStr = "<a href='http://m.aiyueyoo.com/index/index/index/invite_code/".$postObj->EventKey.".html'>爱约游扫码入口!!</a>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    //未关注者关注后推送事件
                    $msgType = "text";
                    $contentStr = "<a href='http://m.aiyueyoo.com'>爱约游扫码入口!!$postObj->EventKey</a>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }
            }else{
                //非扫码入口
                //未关注者关注后推送事件
                $msgType = "text";
                $contentStr = "<a href='http://m.aiyueyoo.com'>爱约游!!$postObj->EventKey</a>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }

           /* if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "<a href='http://m.aiyueyoo.com'>Welcome to Welcome to 爱约游!!</a>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }*/

    }

}
