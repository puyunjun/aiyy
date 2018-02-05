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
            ur.travel_total_time,ur.travel_tool,ur.is_sincerity,ur.sincerity_money,ur.create_time')
            ->order($order)
            ->paginate();

            // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('会员列表') // 设置页面标题
            ->setTableName('user') // 设置数据表名
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

                ['create_time', '发布时间','datetime'],
            ])
            //
            ->addFilter('user_group ug.group_name') // 添加标题字段筛选
            ->addFilter('user_release ur.is_sincerity',['1'=>'交纳',0=>'未交纳']) // 添加标题字段筛选
            ->addOrder('ur.id,ur.total_money')
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

}