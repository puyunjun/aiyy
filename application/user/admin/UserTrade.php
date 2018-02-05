<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\2\5 0005
 * Time: 14:18
 */


/*
 * 会员交易记录控制器
 * */
namespace app\user\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\Recharge;
use app\user\model\home\UpgradeMember;

class UserTrade extends Admin
{


//会员用户升级记录
    public function upgroup_index(){

        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

       /* //转换查询条件
        $map_action = function($map = array()){
            foreach ($map as $k=>$v){
                if(strpos(request()->get('_filter')? request()->get('_filter') : '',$k) !==false){
                    //空白部分后面为需要的表别名以及字段
                    $key =substr($k,strpos($k,' ')+1);
                    $map[$key] = $map[$k];
                    unset($map[$k]);
                }
            }
            return $map;
        };*/
        //得出满足sql的条件
        $map = $this->map_action($map);

        $order = $this->getOrder() ? $this->getOrder() : 'um.id desc';
        // 数据列表
        $data_list = UpgradeMember::where($map)
            ->alias('um')
            ->view('dp_user u','phone,real_name','u.id = um.uid','LEFT')
            ->view('dp_user_group ug_f','group_name as font_group_name','um.font_group_id = ug_f.id','LEFT')
            ->view('dp_user_group ug_b','group_name as back_group_name','um.back_group_id = ug_b.id','LEFT')
            ->field('um.id,um.total_money,um.font_group_id,
            um.back_group_id,um.recharge_type,um.status,um.read_status,um.create_time,um.create_ip')
            ->order($order)
            ->paginate();

        // 分页数据
        $page = $data_list->render();
        //var_dump($data_list);exit;
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('会员列表') // 设置页面标题
            ->setTableName('user') // 设置数据表名
            ->setSearch(['um.id' => 'ID', 'u.phone' => '用户号码']) // 设置搜索参数
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
                ['total_money', '升级消费金额','text'],
                ['recharge_type', '消费类型','text'],
                ['font_group_name', '升级前用户组','text'],
                ['back_group_name', '升级后用户组','text'],
                ['status', '消费状态','callback',function($value){
                    switch ($value){
                        case 1:return '成功';break;
                        default : return '失败';
                    }
                }],
                ['create_time', '升级时间','datetime'],

                ['read_status', '用户是否读取','callback',function($value){
                    switch ($value){
                        case 1:return '已读';break;
                        default : return '未读';
                    }
                }],
                ['create_ip', 'ip(v4)地址','text'],
            ])
            //
            ->addFilter(['upgrade_member um.recharge_type',
                'font_group_name' => 'user_group ug_f.group_name',
                'back_group_name' => 'user_group ug_b.group_name',
                ]) // 添加标题字段筛选
            ->addOrder('um.id,um.total_money')
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }



//会员充值记录列表
    public function recharge_index (){
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

        $order = $this->getOrder() ? $this->getOrder() : 'r.id desc';
        // 数据列表
        $data_list = Recharge::where($map)
            ->alias('r')
            ->view('dp_user u','phone','u.id = r.uid','LEFT')
            ->field('r.id,r.recharge_money,r.recharge_type,r.status,r.create_time,r.create_ip,r.read_status')
            ->order($order)
            ->paginate();
        // 分页数据
        $page = $data_list->render();
        //var_dump($data_list);exit;
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('会员列表') // 设置页面标题
            ->setTableName('user') // 设置数据表名
            ->setSearch(['r.id' => 'ID', 'u.phone' => '用户号码']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['recharge_money', '充值金额','text'],
                ['recharge_type', '充值类型','text'],
                ['status', '充值状态','callback',function($value){
                    switch ($value){
                        case 1:return '成功';break;
                        default : return '失败';
                    }
                }],
                ['create_time', '充值时间','datetime'],
                ['phone', '绑定号码','callback',function($value){
                    $length = strlen($value);
                    $mobile = preg_match_all("/^1[34578]\d{9}$/", $value, $mobiles);
                    if($mobile === intval(0) || $length != 11 ){
                        return '手机未绑定';
                    }else{
                        return $value;
                    }
                }],
                ['read_status', '用户是否读取','callback',function($value){
                    switch ($value){
                        case 1:return '已读';break;
                        default : return '未读';
                    }
                }],
                ['create_ip', 'ip(v4)地址','text'],
            ])
            /* ->addFilter('group_id') // 添加标题字段筛选*/
            ->addOrder('r.id,r.recharge_money')
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }
}