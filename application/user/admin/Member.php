<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\3 0003
 * Time: 10:39
 */

namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\User as UserModel;
use app\user\model\Role as RoleModel;
use app\admin\model\Module as ModuleModel;
use app\admin\model\Access as AccessModel;
class Member extends Admin
{

        public function index(){
            cookie('__forward__', $_SERVER['REQUEST_URI']);

            // 获取查询条件
            $map = $this->getMap();
            $order = $this->getOrder() ? $this->getOrder() : 'u.id asc';
            // 数据列表
            $field_str = 'u.id,sys_id,group_id,member_deadline,city_id,
            phone,user_type,nickname,head_img,real_name,sex
            ,birthday,qq,address,height,account,point,is_escort,login_time,login_ip';
            $data_list = UserModel::where($map)
                    ->alias('u')
                    ->view('dp_user_group ug','group_name','ug.id = u.group_id','LEFT')
                    ->field(trim($field_str))
                    ->order('u.id asc')
                    ->paginate();
            // 分页数据
            $page = $data_list->render();

            // 使用ZBuilder快速创建数据表格
            return ZBuilder::make('table')
                ->setPageTitle('会员列表') // 设置页面标题
                ->setTableName('user') // 设置数据表名
                ->setSearch(['id' => 'ID', 'username' => '用户名']) // 设置搜索参数
                ->addColumns([ // 批量添加列
                    ['id', 'ID'],
                    ['group_name', '权限组','text'],
                    ['sys_id', '约游id','text'],
                    ['member_deadline', '到期时间','callback',function($value){if($value === 0){return '\\';}else{return format_time($value,'Y-m-d');}}],
                    ['city_id', '所属城市','text'],
                    ['phone', '绑定号码','callback',function($value){
                        $length = strlen($value);
                        $mobile = preg_match_all("/^1[34578]\d{9}$/", $value, $mobiles);
                        if($mobile === intval(0) || $length != 11 ){
                            return '手机未绑定';
                        }else{
                            return $value;
                        }
                    }],
                    ['user_type', '用户类型','text'],
                    ['nickname', '昵称','callback','urldecode'],
                    ['head_img', '头像','img_url'],
                    ['real_name', '真实姓名','text'],
                    ['sex', '性别','callback',function($value){
                    switch ($value){
                        case 1:return '男';break;
                        case 2:return '女';break;
                        default : return '保密状态';
                    }
                    }],
                    ['birthday', '生日','date'],
                    ['qq', 'QQ号码','text'],
                    ['address', '常驻地址','text'],
                    ['height', '身高','callback','urldecode'],
                    ['account', '账户余额','text'],
                    ['point', '积分点','text'],
                    ['is_escort','是否伴游','callback',function($value){
                        switch ($value){
                            case 1:return '是';break;
                            //case 4:return '否';break;
                            default : return '否';
                        }
                    }],
                    ['login_time','最后登陆时间','datetime'],
                    ['login_ip','最后登陆ip(v4)地址','text'],
                    ['right_button','操作','btn']
                ])
                ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
                ->addFilter('group_id') // 添加标题字段筛选
                ->addOrder('u.id,ug.group_name')
                ->addRightButtons('edit,delete') // 批量添加右侧按钮
                ->setRowList($data_list) // 设置表格数据
                ->setPages($page) // 设置分页数据
                ->fetch(); // 渲染页面
        }


        public function add(){

        }

}