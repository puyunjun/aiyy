<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\13 0013
 * Time: 11:05
 */

/*
 * 留言控制器
 * */
namespace app\user\home;
use app\user\model\home\ChatContent As ChatContentModel;
use think\Db;
class ChatContent extends Common
{


    public function index(){
        //首先判断用户是否游聊天权限

        $obj_uid = request()->param('obj_uid');
        $date = date('Ym',time());
        //实例化模型，无表则创建表
        $model = new ChatContentModel();
        //调用聊天记录
        //var_dump($model->sel_record($obj_uid,UID,0*5));

        if(request()->isAjax()){
            //获取当前请求的页数 乘上每页显示的数量即为偏移量
            $x = request()->param('page') ? request()->param('page') : 0;
            if($model::where(['receive_uid'=>UID,'send_uid'=>$obj_uid,'read_status'=>0])->count()){
                $x = 0;  //并返回重置页面数
            }
            $data = $model->sel_record($obj_uid,UID,$x*5);
            if($x === 0){
                $result = true;  //标识有未读信息
            }else{
                $result = false;
            }
            $model::where(['receive_uid'=>UID,'send_uid'=>$obj_uid,'read_status'=>0])->setField('read_status',1);
            return json(array('data'=>$data,'has_no_read'=>$result,'page'=>$x));
        }
        $this->assign('start_uid',UID);
        $this->assign('obj_uid',$obj_uid);
        //传递当前用户头像信息

        $this->assign('current_user_img',Db::name('__user__')->where('id',UID)->value('head_img'));
        $this->assign('chat_data',$model->sel_record($obj_uid,UID,0*2));
        //修改未读状态
        $model::where(['receive_uid'=>UID,'send_uid'=>$obj_uid,'read_status'=>0])->setField('read_status',1);
        return $this->fetch();
    }

    //接受留言
    public function add_chat(){
        $data =request()->post();
        $data['send_uid'] = UID;
        $receive_uid = $data['receive_uid'];
        //将两个id排序后生成md5会话标识
        if($data['send_uid'] > $receive_uid){
            $str = $receive_uid.'-'.$data['send_uid'];
        }else{
            $str = $data['send_uid'].'-'.$receive_uid;
        }
        $data['chat_sign'] = $str;
        $data['create_time'] = request()->time();
      $re  = ChatContentModel::insert($data);
      if($re){
          //获取用户头像
          return json(1);
      }else{
          return json(0);
      }

    }
}