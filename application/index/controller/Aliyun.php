<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 11:13
 */

/*阿里云短信类*/

namespace app\index\controller;
require_once dirname(APP_PATH).'/vendor/api_sdk/vendor/autoload.php';
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

class Aliyun
{

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static $acsClient;
    private function __construct(){//声明私有构造方法为了防止外部代码使用new来创建对象。
        // 加载区域结点配置
        Config::load();

        self::$acsClient = $this->getAcsClient();
    }
    private function __clone()
    {

    }

    static public $instance;//声明一个静态变量（保存在类中唯一的一个实例）

    static public function getinstance(){//声明一个getinstance()静态方法，用于检测是否有实例对象
        if(!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public  function getAcsClient($acsClient = null) {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "LTAI1nwEMqb7N3kB"; // AccessKeyId

        $accessKeySecret = "KVB1linVveQG4lysxvlRCNI9hgA624"; // AccessKeySecret


        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if($acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            $acsClient = new DefaultAcsClient($profile);
        }
        return $acsClient;
    }


    public function sendSms($signName, $templateCode, $phoneNumbers, $templateParam = null, $outId = null, $smsUpExtendCode = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        // 可选，设置流水号
        if($outId) {
            $request->setOutId($outId);
        }

        if($smsUpExtendCode) {
            $request->setSmsUpExtendCode($smsUpExtendCode);
        }

        // 发起访问请求
        $acsResponse = self::$acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;

    }


    public  function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId=null) {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumbers);

        // 选填，短信发送流水号
        $request->setBizId($bizId);

        // 必填，短信发送日期，支持近30天记录查询，格式Ymd
        $request->setSendDate($sendDate);

        // 必填，分页大小
        $request->setPageSize($pageSize);

        // 必填，当前页码
        $request->setCurrentPage($currentPage);

        // 发起访问请求
        $acsResponse = self::$acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;
    }

    //生成短信验证码  六位数
    public function generate_code($length = 6) {

        return rand(pow(10,($length-1)), pow(10,$length)-1);

    }
}