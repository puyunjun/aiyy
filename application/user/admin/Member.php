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

            // 数据列表
            $data_list = UserModel::where($map)->order('id asc')->paginate();
            // 分页数据
            $page = $data_list->render();

            // 使用ZBuilder快速创建数据表格
            return ZBuilder::make('table')
                ->setPageTitle('会员列表') // 设置页面标题
                ->setTableName('user') // 设置数据表名
                ->setSearch(['id' => 'ID', 'username' => '用户名']) // 设置搜索参数
                ->addColumns([ // 批量添加列
                    ['id', 'ID'],
                    ['username', '用户名'],
                    ['nickname', '昵称'],
                ])
                ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
                ->addRightButtons('edit,delete') // 批量添加右侧按钮
                ->setRowList($data_list) // 设置表格数据
                ->setPages($page) // 设置分页数据
                ->fetch(); // 渲染页面
        }

}