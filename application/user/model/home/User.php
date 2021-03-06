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
        self::$user_info = request()->session()['user_auth_home'];
    }

    /*获取该用户信息
     * return object 用户信息对象
     * */
    public function user_base_info(){

        //query对象查询
        /*
         * 图片数量
         * */
        $subsql_img = UserVideo::where('video_type=1')->field('uid,count(id) as  img_num')->group('video_type')->buildSql();
        /*
         * 视频数量
         * */
        $subsql_video = UserVideo::where('video_type=2')->field('uid,count(id) as  video_num')->group('video_type')->buildSql();


        $re = $this->alias('User')
            ->where('User.id',UID)
            ->join(['dp_user_attention'=>'attention'],'attention.user_followid=User.id','LEFT')
            ->join(['dp_user_attention'=>'attentioned'],'attentioned.user_id=User.id','LEFT')
            ->join([$subsql_img=> 'img'],'img.uid=User.id','LEFt')
            ->join([$subsql_video=> 'video'],'video.uid=User.id','LEFt')
            ->group('User.id')
            ->field('count(DISTINCT attention.id) as attention_num ,count(DISTINCT attentioned.id) as attentioned_num')
            ->field('img.img_num')
            ->field('video.video_num')
            ->field('User.nickname,User.head_img,User.sys_id,User.id,User.sex')
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
                ->field('U.group_id,member_deadline')
                ->field('allow_priview_list,allow_priview_photo,allow_priview_video,allow_chat')
                ->find();

    }

}