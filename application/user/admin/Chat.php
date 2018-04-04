<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\20 0020
 * Time: 10:52
 */

namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\ChatContent;
use think\Db;
class Chat extends Admin
{

    //系统会员留言列表
    public function index(){

        $map = $this->getMap();
        //查询约游id为0的系统会员留言列表
        //只查询伴游接收到的数据,发送者是否为伴游不管
        $no_read_sql = ChatContent::where($map)
                        ->alias('c1')
                        //->view('dp_user u',['nickname'=>'s_nickname'],'u.id = c.send_uid and u.sys_id = 0','LEFT')
                        //->view('dp_user d',['nickname'=>'r_nickname'],'d.id = c.receive_uid and d.sys_id = 0','LEFT')
                        ->join('__USER__ u1','u1.id = c1.send_uid','LEFT')
                        ->join('__USER__ d1','d1.id = c1.receive_uid and d1.sys_id = 0')
                        ->where('c1.read_status = 0')
                        ->field('count(c1.id) as no_read_num,c1.id')
                        ->buildSql();
                        //->where('c.read_status = 0')

        $data = ChatContent::where($map)
                        ->alias('c')
                        //->view('dp_user u',['nickname'=>'s_nickname'],'u.id = c.send_uid and u.sys_id = 0','LEFT')
                        //->view('dp_user d',['nickname'=>'r_nickname'],'d.id = c.receive_uid and d.sys_id = 0','LEFT')
                        ->join('__USER__ u','u.id = c.send_uid','LEFT')
                        ->join('__USER__ d','d.id = c.receive_uid and d.sys_id = 0')
                        ->join([$no_read_sql=>'no_read'],'no_read.id=c.id','LEFT')
                        //->where('c.read_status = 0')
                        ->field('c.id,c.create_time,no_read.no_read_num,c.content,d.head_img,c.read_status,u.nickname s_nickname,d.nickname r_nickname')
                        ->order('c.create_time desc')
                        ->group('c.chat_sign')
                        ->paginate();
// 分页数据
        $page = $data->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('留言列表') // 设置页面标题
            ->setTableName('chat_content_date') // 设置数据表名
            ->setSearch(['c.id' => 'ID', 'd.nickname' => '伴游昵称']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['s_nickname', '发送者昵称','callback','urldecode'],
                ['r_nickname', '伴游昵称','callback','urldecode'],
                ['head_img', '伴游头像','img_url',],
                ['no_read_num', '是否有未读消息','callback',function($data){
                    if($data){
                        return '<span style="color: #a92222">有'.$data.'条未读</span>';

                    }else{
                        return '没有';
                    }
                }],
                ['content','发送内容','callback',function($res){
                return mb_substr($res,0,5).'...';
                }],
                ['create_time','发送时间','date']

            ])
            ->addColumn('right_button', '操作', 'btn')
            ->addRightButtons(['edit'=> ['title' => '回复']]) // 批量添加右侧按钮
            ->setRowList($data) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }


    //回复消息
    public function edit($id = 0){
            //获取该条数据的发送者id和接受者id
        $data = Db::name('chat_content_date')
                ->alias('c')
                ->join('__USER__ u','u.id = send_uid','LEFT')
                ->join('__USER__ d','d.id = receive_uid','LEFT')
                ->field('send_uid,receive_uid,u.nickname as s_nickname,d.nickname as r_nickname,c.chat_sign')
                ->where('c.id',$id)
                ->find();
        $send_uid = $data['send_uid'];
        $receive_uid = $data['receive_uid'];


        //查询这两个id相关的未读消息
        //获取两个用户的会话标识
        $chat_sign = $data['chat_sign'];

        //修改该标识下未读的状态
        ChatContent::where(['chat_sign'=>$chat_sign,'read_status'=>0])->update(array('read_status'=>1));
        //未读消息
        $info = ChatContent::where(['chat_sign'=>$chat_sign])
                ->alias('c')
                ->join('__USER__ u','u.id = c.send_uid','LEFT')
                ->join('__USER__ d','d.id = c.receive_uid','LEFT')
                ->field('c.content,u.nickname as s_nickname,d.nickname as r_nickname,c.create_time,c.read_status,u.head_img as s_head_img,d.head_img as r_head_img')
                ->order('create_time asc')
                ->select();
        //var_dump($info);exit;
        //已读消息
        if(request()->isAjax()){
            $data = request()->post();
            //修改审核状态
            if($data['send_uid'] > $data['receive_uid']){
                $str = $data['receive_uid'].'-'.$data['send_uid'];
            }else{
                $str = $data['send_uid'].'-'.$data['receive_uid'];
            }
            $data['chat_sign'] = $str;  //用户之间的会话标识

            $data['create_time'] = request()->time();
            $re = ChatContent::insert($data);
            if ($re) return json('回复成功');
            else     return json('回复失败，稍后再试');
        }
        //拼装信息框
        /*
         * @param $info 查询的内容数组
         * */
        $fuc = function($info){
            $data = array();
            foreach ($info as $item) {
                $nickname = $item->s_nickname?$item->s_nickname:$item->r_nickname;
                $data[] = ['text','', urldecode($nickname),date('Y-m-d H:i:s',$item->create_time),$item->content,'',"disabled"];
            }
            return $data;
        };
        $info_arr = $fuc($info);
        return ZBuilder::make('form')
            ->setPageTitle(urldecode($data['s_nickname']).'发送给'.urldecode($data['r_nickname']))
            ->assign(['send_uid' => $send_uid,'receive_uid'=>$receive_uid])
            ->addHidden('send_uid', $receive_uid)
            ->addHidden('receive_uid', $send_uid)
            ->addFormItems($info_arr)
            ->addFormItems([['text','content','消息回复']])
            ->js('reply_info')
            ->hideBtn(['submit', 'back'])
            ->addBtn('<button type="button" onclick="reply()"  class="btn btn-default">回复</button>')
            ->addBtn('<button type="button" onclick="history_chat(\''.$chat_sign.'\')" class="btn btn-default">查看历史记录</button>')
            ->setFormData($info)
            ->fetch();
    }


    //查对应记录
    public function history_chat($chat_sign = 0){
        //获取该条数据的发送者id和接受者id
        $data = Db::name('chat_content_date')
            ->alias('c')
            ->join('__USER__ u','u.id = send_uid','LEFT')
            ->join('__USER__ d','d.id = receive_uid','LEFT')
            ->field('send_uid,receive_uid,u.nickname as s_nickname,d.nickname as r_nickname,c.chat_sign')
            ->where('c.chat_sign',$chat_sign)
            ->find();
        $send_uid = $data['send_uid'];
        $receive_uid = $data['receive_uid'];

        //查询这两个id相关的未读消息
        //获取两个用户的会话标识
        $chat_sign = $data['chat_sign'];

        //全部50条消息
        //获取聊天总计路条数
        $cou = ChatContent::where(['chat_sign'=>$chat_sign,'read_status'=>0])->count();

        if($cou < 50){
            $start = 0;
        }else{
            $start = $cou-50;
        }
        $info = ChatContent::where(['chat_sign'=>$chat_sign,'read_status'=>0])
            ->alias('c')
            ->join('__USER__ u','u.id = c.send_uid','LEFT')
            ->join('__USER__ d','d.id = c.receive_uid','LEFT')
            ->field('c.content,u.nickname as s_nickname,d.nickname as r_nickname,c.create_time,c.read_status,u.head_img as s_head_img,d.head_img as r_head_img')
            ->order('create_time asc')
            ->limit($start,50)
            ->select();

        $fuc = function($info){
            $data = array();
            foreach ($info as $item) {
                $nickname = $item->s_nickname?$item->s_nickname:$item->r_nickname;
                $data[] = ['text','', urldecode($nickname),date('Y-m-d H:i:s',$item->create_time),$item->content,'',"disabled"];
            }
            return $data;
        };
        $info_arr = $fuc($info);
        return ZBuilder::make('form')
            ->setPageTitle(urldecode($data['s_nickname']).'发送给'.urldecode($data['r_nickname']))
            ->addHidden('send_uid', $receive_uid)
            ->addHidden('receive_uid', $send_uid)
            ->addFormItems($info_arr)
            ->hideBtn(['submit', 'back'])
            ->addBtn('<button type="button" onclick="history_chat(\''.$chat_sign.'\')" class="btn btn-default">查看历史记录</button>')
            ->setFormData($info)
            ->fetch();
        var_dump($info);
    }
}