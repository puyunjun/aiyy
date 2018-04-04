<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\2\5 0005
 * Time: 18:00
 */

namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\Db;
use think\Cache;
class Release extends Admin
{

    public function index(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

        //得出满足sql的条件
        $map = $this->map_action($map);

        $order = $this->getOrder() ? $this->getOrder() : 'ur.id desc';
        // 数据列表
        $data_list = Db::name('user_release')->where($map)
            ->alias('ur')
            ->view('dp_user u','phone,real_name,group_id','u.id = ur.uid','LEFT')
            ->view('dp_user_group ug','group_name','u.group_id = ug.id','LEFT')
            ->field('ur.id,ur.release_object,ur.travel_start_time,
            ur.travel_total_time,ur.verify_status,ur.travel_tool,ur.travel_address,ur.is_sincerity,ur.sincerity_money,ur.create_time')
            ->order($order)
            ->paginate();

            // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('会员列表') // 设置页面标题
            ->setTableName('user_release') // 设置数据表名
            ->setSearch(['ur.id' => 'ID', 'u.phone' => '用户号码']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['phone', '绑定号码','callback',function($value){
                    $length = strlen($value);
                    $mobile = preg_match_all("/^1[34578]\d{9}$/", $value, $mobiles);
                    if($mobile === intval(0) || $length != 11 ){
                        return '手机未绑定';
                    }else{
                        return $value;
                    }
                }],
                ['group_name', '会员等级','text'],
                ['real_name', '真实姓名','text'],
                ['travel_start_time', '开始时间','datetime'],
                ['travel_total_time', '出行天数','text'],
                ['travel_tool', '出行工具','text'],
                ['travel_address', '目的地','text'],
                ['is_sincerity', '是否交纳诚意金','callback',function($value){
                    switch ($value){
                        case 0:return '未交纳';break;
                        case 1:return '交纳';break;
                        default : return '';
                    }
                }],
                ['release_object', '约游对象','callback',function($value){
                    switch ($value){
                        case 1:return '男';break;
                        case 2:return '女';break;
                        default : return '不限';
                    }
                }],
                ['sincerity_money', '诚意金额','text'],
                ['verify_status', '审核状态','switch'],

                ['create_time', '发布时间','datetime'],
                ['right_button','操作','btn']
            ])
            //
            ->addFilter('user_group ug.group_name') // 添加标题字段筛选
            ->addFilter('user_release ur.is_sincerity',['1'=>'交纳',0=>'未交纳']) // 添加标题字段筛选
            ->addOrder('ur.id,ur.total_money')
            ->addRightButtons('edit') // 批量添加右侧按钮
            ->addRightButton('delete',['table'=>'user_release']) // 添加删除键,指定表名
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

    //编辑旅途
    public function edit($id=0){

        if(request()->isPost()){
            $data = request()->post();
            $data['create_time'] = request()->time();
            $data['travel_start_time'] = strtotime($data['travel_start_time']);
            if (Db::name('user_release')->update($data)) {
                Cache::clear();
                // 记录行为
                $details = '节点ID('.$id.')';
                action_log('member_edit', 'user_release_edit', $id, UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $map = array(
            'id'=>$id
        );
        $info = Db::name('user_release')->where($map)
            ->field('uid,travel_address,release_object,travel_start_time,travel_total_time,travel_tool,is_sincerity,sincerity_money')
            ->find();
        $res = Db::name('__user__')->where('id',$info['uid'])->field('sys_id,member_deadline')->find();
        if($res['sys_id'] === 0 && $res['member_deadline'] ===0){

            return ZBuilder::make('form')
                ->addHidden('id',$id)
                ->addFormItems([
                    ['text','travel_total_time', '出行天数'],
                    ['date','travel_start_time', '出行时间','<span class="text-danger">格式2018-01-01</span>'],
                    ['text','sincerity_money', '诚意金数额','默认100',100],
                    ['text','travel_tool', '出行方式','如私家车，徒步'],
                    ['text','travel_address', '目的地','如重庆'],
                    ['radio','release_object', '选择出行对象','',['1'=>'男','2'=>'女','0'=>'不限']],
                    ['radio','is_sincerity', '是否交纳诚意金','',['0'=>'不交纳','1'=>'交纳']],
                ])
                ->setFormData($info)
                ->fetch();
        }else{
            $this->error('非系统会员禁止编辑','index');
        }
    }

}