<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\5 0005
 * Time: 11:12
 */

/*
 * 身份认证接口
 * */
namespace app\user\admin;


class IdentifyVerify
{

    //配置您申请的appkey
    protected $appkey = "88b308e6de12b751bee804939758d0bd";

    protected $url = "http://apis.juhe.cn/idimage/verify";

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
   private function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    public function export_init($img_url = ''){
        $params = array(
            "image" =>  base64_encode(file_get_contents($img_url)),
            "key" => $this->appkey,//应用APPKEY(应用详细页查询)
            "side"=>'front'
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($this->url,$paramstring,1);
        $result = json_decode($content,true);
        var_dump($result);
    }

    private function __construct(){//声明私有构造方法为了防止外部代码使用new来创建对象。
        //'http://aiyueyoo.oss-cn-shenzhen.aliyuncs.com/authentication/2018-3-5/15202226282400.png'

    }
    private function __clone()
    {

    }
    static public $instance;//声明一个静态变量（保存在类中唯一的一个实例）

    static public function getinstance(){//声明一个getinstance()静态方法，用于检测是否有实例对象
        if(!self::$instance) self::$instance = new self();
        return self::$instance;
    }

}