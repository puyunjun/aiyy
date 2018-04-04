<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017\12\23 0023
 * Time: 11:50
 */

namespace app\user\model\home;
use think\Model;
use think\helper\Hash;
use think\Db;

class User extends Model
{

    protected $name="user";

    private static $user_info;

    public function __construct($data = [])
    {
        parent::__construct($data);

        self::$user_info = session('user_auth_home') ? session('user_auth_home') : '';

    }

    /*获取该用户信息
     * return object 用户信息对象
     * */
    public function user_base_info(){

        //query对象查询
        /*
         * 图片数量
         * */
        $subsql_img = UserVideo::where(array('uid'=>UID,'video_type'=>1))->field('uid,count(id) as  img_num')->group('video_type')->buildSql();
        /*
         * 视频数量
         * */
        $subsql_video = UserVideo::where(array('uid'=>UID,'video_type'=>2))->field('uid,count(id) as  video_num')->group('video_type')->buildSql();


        $re = $this->alias('User')
            ->where('User.id',UID)
            ->join(['dp_user_attention'=>'attention'],'attention.user_followid=User.id','LEFT')
            ->join(['dp_user_attention'=>'attentioned'],'attentioned.user_id=User.id','LEFT')
            ->join([$subsql_img=> 'img'],'img.uid=User.id','LEFT')
            ->join([$subsql_video=> 'video'],'video.uid=User.id','LEFT')
            ->join('dp_user_group dg','dg.id=User.group_id','LEFT')
            ->group('User.id')
            ->field('count(DISTINCT attention.id) as attention_num ,count(DISTINCT attentioned.id) as attentioned_num')
            ->field('img.img_num')
            ->field('video.video_num')
            ->field('User.nickname,User.head_img,User.sys_id,User.id,User.sex,dg.icon')
            ->find();

            //解码用户昵称
        $re['nickname'] = urldecode($re['nickname']);
        return $re;
    }


    /*用户权限相关信息
     * @param int $uid 当前会员的uid
     *
     * */
    public static function user_privilege($uid = UID){
            //返回会员组id，截至时间,以及允许权限信息

            return self::where('U.id',$uid)
                ->alias('U')
                ->join('dp_user_group_privilege UP','UP.group_id = U.group_id','LEFT')
                ->field('U.group_id,U.member_deadline,U.sys_id')
                ->field('UP.allow_priview_list,UP.allow_priview_photo,UP.allow_priview_video,UP.allow_chat')
                ->find();

    }


    //查询所有伴游用户信息
    /*
     * @param int $page 请求分页数量
     * */
    public function escort_info($page = 0){
        /*
         * 图片数量
         * */
        $subsql_img = UserVideo::where('video_type=1')->field('uid,count(id) as  img_num')->group('uid')->buildSql();
        /*
         * 视频数量
         * */
        $subsql_video = UserVideo::where('video_type=2')->field('uid,count(id) as  video_num')->group('uid')->buildSql();


        $re = $this->alias('User')
            ->where('User.is_escort=1 and ua.status = 1')
            ->join([$subsql_img=> 'img'],'img.uid=User.id','LEFT')
            ->join('user_auth ua','ua.uid = User.id')
            ->join([$subsql_video=> 'video'],'video.uid=User.id','LEFT')
            ->join('dp_user_identity Uit','Uit.uid=User.id','LEFT')
            ->group('User.id')
            ->field('img.img_num')
            ->field('video.video_num')
            ->field('Uit.id_card_num')
            ->field('User.nickname,User.sys_id,User.birthday,User.real_name,User.head_img,User.address,User.id,User.sex,User.height,User.login_addr_x,User.login_addr_y')
            ->order('User.id desc')
            ->limit($page*6,6)
            ->select();
       return $re;
    }

    //查询伴游详细信息
    public function escort_base_info($uid = ''){
        $escort_info = User::where('U.id',$uid)
            ->alias('U')
            -> join('dp_user_video dv','dv.uid = U.id','LEFT')
            -> join('dp_user_identity di','di.uid = U.id','LEFT')
            ->field('U.id,U.sys_id,U.birthday,U.nickname,U.head_img,U.height,U.address,U.weight,U.login_addr_x,U.login_addr_y,U.occupation_id,U.forword')
            ->field('dv.video_url,dv.video_type')
            ->field('di.id_card_num')
            ->select();
        $data = array();
        $data['nickname'] = urldecode($escort_info[0]->nickname);
        $data['uid'] = $escort_info[0]->id;
        $data['head_img'] = $escort_info[0]->head_img;
        $data['height'] = $escort_info[0]->height;
        $data['address'] = explode(" ",$escort_info[0]->address)[0];
        $data['login_addr_x'] = $escort_info[0]->login_addr_x;
        $data['login_addr_y'] = $escort_info[0]->login_addr_y;
        $data['occupation_id'] = $escort_info[0]->occupation_id;
        $data['sys_id'] = $escort_info[0]->sys_id;
        $data['birthday'] = $escort_info[0]->birthday;
        $data['forword'] = $escort_info[0]->forword;
        $data['weight'] = $escort_info[0]->weight;
        $data['id'] = $escort_info[0]->id;

        //计算生日
        $birthday_format = isset(getIDCardInfo($escort_info[0]->id_card_num)['birthday']) ? getIDCardInfo($escort_info[0]->id_card_num)['birthday']:0;
        //若为系统添加伴游,直接通过伴游生日计算
        if($data['sys_id'] === 0){
            $birthday_format = date('Y-m-d',$data['birthday']);
        }

        $data['birthday'] = $birthday_format ? abs(date('Y', time()) - date('Y', strtotime($birthday_format))) : 0;

        //计算数量
        $amount = function($v_num = 0){
            $v_num++;
            return $v_num;
        };
        foreach ($escort_info as $k=>$v){
            if($v->video_type === 1){
                $data['photo_url'][]=$v->video_url;
                //计算照片数
                $data['photo_num'] = isset($data['photo_num']) ? ++$data['photo_num'] : 1;
            }
            if($v->video_type === 2){
                $data['video_url'][]=$v->video_url;
                //计算视频数
                $data['video_num'] = $amount(isset($data['video_num']) ? $data['video_num'] : 0);
            }
        }
        //得出信息数组  $data

        //var_dump($data);exit;
        return $data;
    }

}