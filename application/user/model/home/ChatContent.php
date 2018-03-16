<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\14 0014
 * Time: 10:52
 */

namespace app\user\model\home;
use think\Model;
use think\Request;
use think\Db;

class ChatContent extends Model
{

    protected  $name = 'chat_content_date';


    //查询与对象的聊天记录 取出最后五条记录，按升序排序
    /*
     * @param int $obj_uid 对方用户uid，
     * @param int $uid当前用户uid
     * @param int $x 请求刷新的偏移量起始位置 默认为0
     * */
    public function sel_record($obj_uid,$uid,$x=0){

      $sql = 'select a.id,a.content,a.send_uid,a.receive_uid,a.create_time,u.head_img as s_head_img,du.head_img as r_head_img from
                (select id,send_uid,receive_uid,content,create_time
                 from dp_chat_content_date 
                 where (send_uid = '.$uid.' and receive_uid = '.$obj_uid.')
                 or (send_uid = '.$obj_uid.' and receive_uid = '.$uid.')
                 order by id desc limit '.$x.',5) 
                as a 
                join dp_user as u on u.id = a.send_uid
                join dp_user as du on du.id = a.receive_uid
                order by a.create_time asc';
        return Db::query($sql);
    }
}