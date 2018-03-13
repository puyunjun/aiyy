<?php
namespace wxpay;

use wxpay\database\WxPayJsApiPay;

/**
 * JSAPI支付实现类
 * 该类实现了从微信公众平台获取code、通过code获取openid和access_token、
 * 生成jsapi支付js接口所需的参数、生成获取共享收货地址所需的参数
 * 该类是微信支付提供的样例程序，商户可根据自己的需求修改，或者使用lib中的api自行开发
 *
 * Class JsApiPay
 * @package wxpay
 * @author goldeagle
 */
class JsApiPay
{
    /**
     *
     * 网页授权接口微信服务器返回的数据，返回样例如下
     * {
     *  "access_token":"ACCESS_TOKEN",
     *  "expires_in":7200,
     *  "refresh_token":"REFRESH_TOKEN",
     *  "openid":"OPENID",
     *  "scope":"SCOPE",
     *  "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
     * }
     * 其中access_token可用于获取共享收货地址
     * openid是微信支付jsapi支付接口必须的参数
     * @var array
     */
    public $data = null;
    public $curl_timeout = 5;

    /**
     *
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     * @param array() $order_info 订单或者其他相关参数
     * @param string $scope_type 获取用户信息类型
     * @return string 用户的openid
     */
    public function getOpenid($order_info = array())
    {
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $url = $this->__createOauthUrlForCode($baseUrl);
            //定义携带的参数
            $state = json_encode($order_info);
            $url = $order_info ? str_replace("STATE", $state, $url) : $url;
            header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;   //信息数组
        }
    }

    /**
     *
     * 获取jsapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws WxPayException
     *
     * @return string json数据，可直接填入js函数作为参数
     */
    public function getJsApiParameters($UnifiedOrderResult)
    {
        if (!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == ""
        ) {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->setAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->setTimeStamp("$timeStamp");
        $jsapi->setNonceStr(WxPayApi::getNonceStr());
        $jsapi->setPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->setSignType("MD5");
        $jsapi->setPaySign($jsapi->makeSign());
        $parameters = json_encode($jsapi->getValues());
        return $parameters;
    }

    /**
     *
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     *
     *
     */
    public function getOpenidFromMp($code)
    {
        $url = $this->__createOauthUrlForOpenid($code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if (WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && WxPayConfig::CURL_PROXY_PORT != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch, CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        }
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res, true);
        $this->data = $data;
        //$openid = $data['openid'];
        //return $openid;
        return $data;
    }

    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     * @return string 返回已经拼接好的字符串
     */
    private function toUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v) {
            if ($k != "sign") {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     *
     * 获取地址js参数
     * @return string 获取共享收货地址js函数需要的参数，json格式可以直接做参数使用
     */
    public function getEditAddressParameters()
    {
        $getData = $this->data;
        $data = array();
        $data["appid"] = WxPayConfig::APPID;
        $data["url"] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $time = time();
        $data["timestamp"] = "$time";
        $data["noncestr"] = "1234568";
        $data["accesstoken"] = $getData["access_token"];
        ksort($data);
        $params = $this->toUrlParams($data);
        $addrSign = sha1($params);

        $afterData = array(
            "addrSign" => $addrSign,
            "signType" => "sha1",
            "scope" => "jsapi_address",
            "appId" => WxPayConfig::APPID,
            "timeStamp" => $data["timestamp"],
            "nonceStr" => $data["noncestr"]
        );
        $parameters = json_encode($afterData);
        return $parameters;
    }

    /**
     *
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     *
     * @return string 返回构造好的url
     */
    private function __createOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = WxPayConfig::APPID;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE" . "#wechat_redirect";
        $bizString = $this->toUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
    }

    /**
     *
     * 构造获取open和access_toke的url地址
     * @param string $code ，微信跳转带回的code
     *
     * @return string 请求的url
     */
    private function __createOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = WxPayConfig::APPID;
        $urlObj["secret"] = WxPayConfig::APPSECRET;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->toUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?" . $bizString;
    }

    /*
     * 获取用户详细信息
     * @param array $point 经纬度坐标
     * */

    public function _getUserInfo($point = ''){

        if(!isset($_GET['code'])){
            //$scope='snsapi_userinfo';//需要授权
            $appid='wx1800872e18acc8f7';
            $redirect_uri = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect

            $state = $point ? json_encode($point,true) : 1;

            $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";

            header("Location:".$url);

            exit;
        }else{
            $code = $_GET["code"];
            $appid = WxPayConfig::APPID;
            $secret = WxPayConfig::APPSECRET;

            $state = $_GET['state'];

            /*$get_point = function ($state){
                $state = ltrim($state,'{');
                $state = rtrim($state,'}');
                $state = explode(',',$state);
                foreach ($state as $key=>$v){
                    $a = explode(':',$v);
                    foreach ($a as $k=>$val){
                        if(isset($a[$k+1])){
                            $state[$a[$k]]=$a[$k+1];
                        }
                    }
                    unset($state[$key]);
                }
                return $state;
            };*/

            $state = json_decode($state,true);

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
            $userinfo['token'] = $token;
            $userinfo['state'] = $state;
            return $userinfo;
        }

    }


        private function getJson($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            return json_decode($output, true);
        }
}