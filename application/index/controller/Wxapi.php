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

    public function get_code(){
        //$scope='snsapi_userinfo';//需要授权
        $appid='wx1800872e18acc8f7';
        $redirect_uri = urlencode('http://m.aiyueyoo.com/index/Wxapi/get_code_openid');
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
        $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";

        header("Location:".$url);

        exit;
    }

}
