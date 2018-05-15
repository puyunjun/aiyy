<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\5\2 0002
 * Time: 10:28
 */

namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\AdminChat AS AdminChatModel;
use think\Db;
class AdminChat extends  Admin
{

    /*
     *
     * 聊天客服管理
     * */

    public function index(){

        $map = $this->getMap();
            //获取人数数据
        $people =    Db::name('admin_chat_content')
                        ->alias('acc')
                    ->where($map)
                    ->join('dp_admin_user au','au.id = acc.admin_uid','LEFT')
                    ->join('dp_user u','u.id = acc.uid','LEFT')
                    ->field('au.id,au.nickname,count(u.id) as uid_num ,acc.update_time,acc.admin_uid')
                    ->group('acc.admin_uid')
                    ->paginate();

        $page = $people->render();
        $admin_chat_user = Db::name('admin_user')->column('id,nickname');
        //var_dump($admin_chat_user);exit;
        return ZBuilder::make('table')
            ->setPageTitle('客服管理列表') // 设置页面标题
            ->setTableName('admin_chat_content') // 设置数据表名
            ->setSearch('au.nickname','客服昵称') // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', '后台管理员ID'],
                ['nickname', '客服昵称','text'],
                ['uid_num','管理人数','text'],
            ])
            ->addTopButtons('add') // 批量添加顶部按钮
             ->addColumn('right_button', '操作', 'btn')
            ->addRightButtons(['edit'=> ['title' => '查看详情']]) // 批量添加右侧按钮
            ->setRowList($people) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

    public function add(){
        if(request()->isPost()){
            //获取添加的客服id
            $result = $this->validate(request()->post(), 'AdminChatValidate');
            if(true !== $result) $this->error($result);
            $data = request()->post()['uid'];
            $id_data =[];

            foreach ($data as $k=>$v){
                $id_data[$k]['admin_uid'] = request()->post()['admin_uid'];
                $id_data[$k]['uid'] = $v;
                $id_data[$k]['update_time'] = request()->time();
            }
            if ($re = Db::name('admin_chat_content')->insertAll($id_data)) {

                $this->success('新增成功', 'index');

            } else {
                $this->error('新增失败');
            }
        }

        $admin_chat_user = Db::name('admin_chat_content')->column('uid');

        //已选择的系统用户id
        $has_chat_user_id = Db::name('admin_chat_content')->column('uid');


        $has_chat_user = $has_chat_user_id ? implode(',',$has_chat_user_id) : 0;



        //该管理员已有的系统用户信息
        $has_chat_user_info = Db::name('__user__')->where('id in ('.$has_chat_user.')')->column('id,nickname');

        foreach ($has_chat_user_info as $k=>$val){
            $has_chat_user_info[$k] = urldecode($val);
        }
        //var_dump($has_chat_user);exit;

        //还剩下的系统用户id以及信息
        $has_id = Db::name('__user__')
            ->where('sys_id = 0 AND member_deadline = 0')
            ->column('id,nickname');

        foreach ($has_id as $key=>$item) {
            foreach ($admin_chat_user as $k=>$value){
                if($value !== '0'){
                    if($value == $key){
                        unset($has_id[$key]);
                    }
                }
            }
        }
        foreach ($has_id as $k=>$val){
            $has_id[$k] = urldecode($val);
        }
        //选择管理员
        $all_admin_chat_user = Db::name('admin_user')->column('id,username');

        return ZBuilder::make('form')
            //->setTabNav($list_tab,  $group)
            ->addRadio('admin_uid', '可选择管理员', '', $all_admin_chat_user)
            ->addCheckbox('uid', '可选择的系统会员', '', $has_id)
            ->fetch();
    }

    /*
     * param int $id 后台管理员id
     * */
    public function edit($id = '')
    {

        //查询该该管理员管理员的会员
        $admin_has_user = Db::name('admin_chat_content')->where('')->column('uid');
        $map = $this->getMap();

        $re = Db::name('admin_chat_content')
            ->alias('acc')
            ->where($map)
            ->join('dp_admin_user au','au.id = acc.admin_uid','LEFT')
            ->join('dp_user u','u.id = acc.uid','INNER')
            ->where('acc.admin_uid = '.$id)
            ->field('acc.id,au.nickname,acc.update_time,acc.admin_uid,u.nickname as unickname')
            ->paginate();

        $page = $re->render();
        $admin_chat_user = Db::name('admin_user')->column('id,nickname');
        //var_dump($admin_chat_user);exit;
        return ZBuilder::make('table')
            ->setPageTitle('客服管理列表') // 设置页面标题
            ->setTableName('admin_chat_content') // 设置数据表名
            ->setSearch('au.nickname','客服昵称') // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['admin_uid','客服昵称','select',$admin_chat_user],
                ['unickname', '系统会员昵称','text'],
            ])
            ->addColumn('right_button', '操作', 'btn')
            ->addRightButtons(['delete']) // 批量添加右侧按钮
            ->setRowList($re) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

}