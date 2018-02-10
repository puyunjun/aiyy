<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\18 0018
 * Time: 10:21
 */

namespace app\user\home;

use app\admin\model\Config;
use think\Controller;
use app\user\model\home\User;
use app\user\model\home\Privilege;
use app\user\model\home\Video;
use think\Db;
use think\File;
use app\user\validate\MyInfo As UserMyInfo;
use app\index\controller\AliyunOss;
use OSS\Core\OssUtil;
use think\Session;
use think\Validate;
class MyInfo extends Common
{

    private static $OssUpload;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        self::$OssUpload = AliyunOss::getinstance()->OssUpload();
    }
    public function index(){

        //当前用户基本信息
        $userInfo = User::get(UID);
        $length = strlen($userInfo['phone']);
        $mobile = preg_match_all("/^1[34578]\d{9}$/", $userInfo['phone'], $mobiles);
        if($mobile === intval(0) || $length != 11 ){
            $userInfo->phone = '手机未绑定';
        }

        //解码用户昵称
        $userInfo->nickname = urldecode($userInfo->nickname );
        $this->assign('base_info',$userInfo);

        $meta_data = $this->media_group(Video::where('uid',UID)->select());
        //照片传值

        $this->assign("photo_url_arr", isset($meta_data['photo'])?$meta_data['photo']:array());
        //视频传值
        $this->assign("video_url_arr", isset($meta_data['video'])?$meta_data['video']:array());

        //配置阿里云上传文件夹名
        $this->assign('dir_name',config('dir_name'));
        return $this->fetch();
    }


    //图片和照片分开
    /*
     * @param array $media_data 用户图片视频数据
     * */
    private function media_group($media_data = array()){

        $data = [];
        foreach ($media_data as $k=>$v){
            if($v->video_type === 1){
                //照片分组
                $photo = [];
                $photo['id'] = $v['id'];
                $photo['video_url'] = $v['video_url'];
                $data['photo'][] = $photo;
            }else{
                //视频分组
                $video = [];
                $video['id'] = $v['id'];
                $video['video_url'] = $v['video_url'];
                $data['video'][] = $video;
            }
        }
        return $data;
    }

    //上传接口
    public function uploadHeadImg($filePath = '')
    {
        /*上传函数*/
        //调用上传接口  用于用户上传头像
        $bucket = 'aiyueyoo'; //阿里云上传模版名
        $object = 'head/'.date('Y-m-d',time()).'/'.time() . UID . '.png';
        //$filePath = $_FILES['file']['tmp_name'];
        try {
            $res = self::$OssUpload->uploadFile($bucket, $object, $filePath);
            //$res = $this->putObjectByRawApis(self::$OssUpload,$bucket,$object,$filePath);  分片参考
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
        }
        return $res;
    }
    public function modify()
    {

        $file = request()->file('image');

        $data = $this->get_data();
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file) {

            $res_up =  $this->uploadHeadImg($file->getPathname());
            $data['head_img'] = $res_up['info']['url'];

        }
        $validate = $this->validate($data,'MyInfo');
        if($validate !== true ){
            return json($validate);
        }

        if(User::user_privilege(UID)->group_id === 0){
            //权限判断 查找分组id
            $group_id = Privilege::where(array('allow_priview_list'=>1,'allow_priview_photo'=>4))->value('group_id');
            $data['group_id'] = $group_id;
        }
        $result = User::where('id',UID)->update($data);
        if($result !== false){
            return json(true);
        }

    }
    private function get_data(){
        $data = [
            'nickname'   =>   urlencode(request()->post('nickname')),                              // 昵称
            'autograph'  =>   request()->post('autograph'),                            //个性签名
            'real_name'  =>   request()->post('real_name'),                             //姓名
            'city_id'    =>   request()->post('city_id'),                               //地址
            'qq'    =>         request()->post('qq'),                                    //qq
            'height' =>        request()->post('height'),                               //身高
            'measurement' =>   request()->post('measurement'),                          //三围
            'weight' =>        request()->post('weight'),                               //体重
            'interest' =>      request()->post('interest'),                            //爱好
            'occupation_id_id' => request()->post('occupation_id'),                         //职业
            'address' =>        request()->post('address'),                                   //出没地 ********
            'birthday' => strtotime(request()->post('birthday'))?strtotime(request()->post('birthday')):'',                          //生日
        ];
        return $data;
    }
    public function show($id)
    {           //获取点击表格id
        $data['id'] = $id;
        $user = Db::name('user')->where('id', UID)->find();
        $this->assign('user', $user);
        $this->assign("id", $data);
        return $this->fetch();
    }

    private function validate_update(){
        return   $validate_data = array(
            'nickname'=>array(
                'rule'=>
                    array(
                        'nickname'  => 'require|max:255',
                    ),
                'msg'=>array(
                    'nickname.require' => '名称必须',
                    'nickname.max'     => '名称最多不能超过25个字符',
                ),
                'data'=>array(
                    'nickname'  => urlencode(request()->post('nickname')),
                ),
            ),
            'autograph'=>array(
                'rule'=>
                    array(
                        'autograph'  => 'require|max:255',
                    ),
                'msg'=>array(
                    'autograph.require' => '签名必须',
                    'autograph.max'     => '签名最多不能超过25个字符',
                ),
                'data'=>array(
                    'autograph'  => urlencode(request()->post('autograph')),
                ),
            ),
            'real_name'=>array(
                'rule'=>
                    array(
                        'real_name'  => 'require|max:255',
                    ),
                'msg'=>array(
                    'real_name.require' => '姓名必须',
                    'real_name.max'     => '姓名最多不能超过25个字符',
                ),
                'data'=>array(
                    'real_name'  => urlencode(request()->post('real_name')),
                ),
            ),
            'birthday'=>array(
                'rule'=>
                    array(
                        'birthday'  => 'require',
                    ),
                'msg'=>array(
                    'birthday.require' => '生日必须',
                ),
                'data'=>array(
                    'birthday'  => request()->post('birthday'),
                ),
            ),
            'city_id'=>array(
                'rule'=>
                    array(
                        'city_id'  => 'require'
                    ),
                'msg'=>array(
                    'city_id.require' => '地址必须',
                ),
                'data'=>array(
                    'city_id'  => request()->post('city_id'),
                ),
            ),
            'occupation_id'=>array(
                'rule'=>
                    array(
                        'occupation_id'  => 'require',
                    ),
                'msg'=>array(
                    'occupation_id.require' => '职业必须',
                ),
                'data'=>array(
                    'occupation_id'  => request()->post('occupation_id'),
                ),
            ),
            'qq'=>array(
                'rule'=>
                    array(
                        'qq'=>'require|number|length:4,11'
                    ),
                'msg'=>array(
                    'qq.require' => '请输入正确qq',
                    'qq.number'     => '请输入正确qq',
                    'qq.length'     => '请输入正确qq',
                ),
                'data'=>array(
                    'qq'  => urlencode(request()->post('qq')),
                ),
            ),
            'interest'=>array(
                'rule'=>
                    array(
                        'interest'  => 'require'
                    ),
                'msg'=>array(
                    'interest.require' => '爱好必须',
                ),
                'data'=>array(
                    'interest'  => request()->post('interest'),
                ),
            ),
            'address'=>array(
                'rule'=>
                    array(
                        'address'  => 'require'
                    ),
                'msg'=>array(
                    'address.require' => '常出没地必须',
                ),
                'data'=>array(
                    'address'  => request()->post('address'),
                ),
            ),
            'height'=>array(
                'rule'=>
                    array(
                        'height'=>'require|number|between:50,300'
                    ),
                'msg'=>array(
                    'height.require'     => '请输入正确的身高',
                    'height.number'     => '请输入正确的身高',
                    'height.between'     => '请输入正确的身高',
                ),
                'data'=>array(
                    'height'  => urlencode(request()->post('height')),
                ),
            ),
            'measurement'=>array(
                'rule'=>
                    array(
                        'measurement'  => 'require'
                    ),
                'msg'=>array(
                    'measurement.require' => '三围必须',
                ),
                'data'=>array(
                    'measurement'  => urlencode(request()->post('measurement')),
                ),
            ),
            'weight'=>array(
                'rule'=>
                    array(
                        'weight'=>'require|between:30,300'
                    ),
                'msg'=>array(
                    'weight.require'     => '体重必须',
                    'weight.between'     => '请输入正确的体重'

                ),
                'data'=>array(
                    'weight'  => urlencode(request()->post('weight')),
                ),
            ),
        );
    }

    public function mody($id)
    {
        $get_date = request()->post();
        $key_name = array_keys($get_date)[0];
        $data = &$this->validate_update()[$key_name];
        $validate = new Validate($data['rule'], $data['msg']);
        $result = $validate->check($data['data']);
        if (!$result) {
            return json($validate->getError());   //返回验证消息
            // return $this->redirect('http://www.aiyy.com/user/my_info/show/id/1.html');
        } else {

            if ($key_name != 'birthday') {
                db('user')->where('id', UID)->update(["$key_name" => request()->post("$key_name")]);
            } else {
                $time = strtotime(request()->post("$key_name"));
                db('user')->where('id', UID)->update(["$key_name" => $time]);
            }

            return json(true);
            // $this->redirect('user/my_info/index');
        }
    }



    //用户上传个人照方法
    public function up_gr_photo(){

        if($this->request->isAjax()){

            //接受上传的数据
            $up_data = request()->post()['up_data'];
            $data = [];
            foreach ($up_data as $k=>$v){
                $data[$k]['video_url'] = $v;
                $data[$k]['video_type'] = 1;
                $data[$k]['create_time'] = request()->time();
                $data[$k]['uid'] = UID;
                $data[$k]['upload_ip'] = get_client_ip(1);  //数字型ip地址
            }
            //添加数据
            if(Video::insertAll($data)) return  json('添加成功');

        }

    }


    //用户删除照片本地数据
    public function delete_photo(){
        $id = request()->post('id');

        $re = Video::where('id',$id)->delete();
        if($re){
            return json(array('code'=>200,'msg'=>'删除成功'));
        }else{
            return json(false);
        }

    }
}