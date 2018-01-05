<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\5 0005
 * Time: 11:12
 */

namespace app\index\controller;
require_once VENDOR_PATH.'/AliyunOss/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;
class AliyunOss
{
    private static $accessKeyId = "LTAIOm1dp8BxHuPE";//您从OSS获得的AccessKeyId
    private static $accessKeySecret = "dBCJRxA2ZAzZXjiWhP1lBkbP4GngMP";//您从OSS获得的AccessKeySecret
    private static $endpoint = "oss-cn-beijing.aliyuncs.com";//您选定的OSS数据中心访问域名，例如http://oss-cn-hangzhou.aliyuncs.com
    static public $instance = NULL; //声明一个静态变量（保存在类中唯一的一个实例）
    private function __construct(){//声明私有构造方法为了防止外部代码使用new来创建对象。

    }
    private function __clone(){//防止被克隆

    }

    static public function getinstance(){   //声明一个getinstance()静态方法，用于检测是否有实例对象
        if(!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    //上传方法
    public static function OssUpload(){
        try {
            return $ossClient = new OssClient(self::$accessKeyId, self::$accessKeySecret, self::$endpoint);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return $e->getMessage();
        }
    }

}
