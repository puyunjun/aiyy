<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\16 0016
 * Time: 18:45
 */
/*
 * 个人认证控制器
 * */
namespace app\user\home;
use app\user\model\home\Authentication As AuthenticateModel;
use app\user\model\home\User;
use think\Request;

class Authentication extends Common
{

    private $host;
    private $id;
    private $key;
    private $dir;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->host = 'http://aiyueyoo.oss-cn-shenzhen.aliyuncs.com';
        $this->id= 'LTAISOppe6PCaBon';
        $this->key= 'lsQnXpotS3e8UDh15568oUid8aOPv8';
        $this->dir= 'user-dir/';
    }

    public function index(){
        //获取用户姓名性别

        $userInfo = User::where('u.id',UID)
                    ->alias('u')
                   ->join('dp_user_identity ui','ui.uid = u.id','LEFT')
                    ->field('ui.status,u.is_bind_phone,u.sex,u.real_name,ui.id_card_num,ui.sfz_font_img,ui.sfz_back_img,ui.sfz_hand_img')
                    ->find();
        if(request()->isAjax()){

            if(!$userInfo->real_name || !$userInfo->is_bind_phone){
                return json(array('code'=>103,'msg'=>'未完善真实姓名和手机绑定，是否完善'));
                //$this->redirect('user/index/index',['param_name'=>'Authentication_data'],'302',['Authentication_data'=>'请至少完善真实姓名和手机绑定']);
            }
        }
        if(!$userInfo->real_name || !$userInfo->is_bind_phone){
            //不满足条件直接跳转回去;
            $this->redirect('user/index/index');
        }
        $this->assign('uid',UID);
        $this->assign('userInfo',$userInfo);
        return $this->fetch();
    }
    public function upload(){
        return json(array('a'=>1));
    }

    /*
     * 上传证件照方法
     * */
    public function up_authenticate(){
       $data = request()->post();
       $data ['create_time'] = request()->time();
       $data ['uid'] = UID;
       $map = array('uid'=>UID);
       if(AuthenticateModel::get($map)){
           $data['update_time'] = request()->time();
          $re =  AuthenticateModel::where('uid',UID)->update($data);
       }else{
            AuthenticateModel::insert($data);
           $re =AuthenticateModel::getLastInsID();
       }
       return json($re);
    }


    //oss上传后端签名方法1
    public function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

    //oss上传后端签名方法2
    public function get_oss_up(){
        $id= $this->id;
        $key= $this->key;
        $host = $this->host;

        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        $dir = $this->dir;

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        echo json_encode($response);
    }
}