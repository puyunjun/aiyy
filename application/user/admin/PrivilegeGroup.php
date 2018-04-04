<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\30 0030
 * Time: 10:19
 */

namespace app\user\admin;
use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\PrivilegeGroup As PrivilegeGroupModel;
use think\Db;
class PrivilegeGroup extends Admin
{



    public function index(){

        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

        // 数据列表
        $data_list = PrivilegeGroupModel::where($map)->order('id asc')->paginate();

        // 分页数据
        $page = $data_list->render();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('分组列表') // 设置页面标题
            ->setTableName('user_group') // 设置数据表名
            ->setSearch(['id' => 'ID', 'group_name' => '会员组名称']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['group_name', '会员组名称','link', url('aaaa/aa/group_name')],
                ['price_y', '月费价格','text.edit'],
                ['price_m', '半年费价格','text.edit'],
                ['price_a', '年费价格','text.edit'],
                ['prestore', '预存金额','text.edit'],
                ['gift_money', '赠送金额','text.edit'],
                ['member_type', '会员类型','select',['1'=>'线上会员','2'=>'线下会员']],
                ['discount_y', '月费折扣','text.edit'],
                ['discount_m', '半年费折扣','text.edit'],
                ['discount_a', '年费折扣','text.edit'],
                ['discount_pre', '预存制折扣','text.edit'],
                ['icon', '会员图标','img_url'],
                ['usernamecolor', '会员名字颜色','callback',function($value){
                return "<span style='color: ".$value."'>会员名字</span>";
                }],
                ['description', '相关描述'],
                ['sort', '排序','text.edit'],
                ['create_time', '录入时间','datetime'],
                ['update_time', '编辑时间','datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,delete') // 批量添加顶部按钮
            ->addRightButtons('edit,delete') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面

    }


    public function add(){
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            $data['create_time'] = request()->time();

            // 验证
           $sence_num = $data['member_type']? $data['member_type'] : 1;

            $result = $this->validate($data, 'PrivilegeGroup.add'.$sence_num);

            if($sence_num === 1){
                //充值会员
                unset($data['prestore']);
                unset($data['gift_money']);
                unset($data['discount_pre']);
            }else{
                //预存会员
                unset($data['price_y']);
                unset($data['price_m']);
                unset($data['price_a']);
                unset($data['discount_y']);
                unset($data['discount_m']);
                unset($data['discount_a']);
            }
            if(true !== $result) $this->error($result);

            $data['icon']  = get_file_path($data['icon']);
            //本站直接存附件id  查询表 attachment即可

            if ($column = PrivilegeGroupModel::create($data)) {
                cache('cms_column_list', null);
                // 记录行为
                action_log('column_add', 'cms_column', $column['id'], UID, $data['group_name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        // 使用ZBuilder构建表单页面，并将页面标题设置为“添加”
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'group_name', '分组名称', '<span class="text-danger">必填</span>'],
                ['select', 'member_type', '会员类型','',['1'=>'线上会员','2'=>'线下会员']],
                ['text', 'price_y', '月费价格','<span class="text-danger">必填</span>'],
                ['text', 'price_m', '半年费价格','<span class="text-danger">必填</span>'],
                ['text', 'price_a', '年费价格','<span class="text-danger">必填</span>'],
                ['text', 'prestore', '需预存金额','<span class="text-danger">必填</span>'],
                ['text', 'gift_money', '赠送金额','<span class="text-danger">必填</span>'],
                ['text', 'discount_y', '月费折扣'],
                ['text', 'discount_m', '半年费折扣'],
                ['text', 'discount_a', '年费折扣'],
                ['text', 'discount_pre', '预存制折扣'],
                ['image', 'icon', '会员图标'],
                ['colorpicker', 'usernamecolor', '会员名字颜色'],
                ['text', 'description', '相关描述'],
                ['text', 'sort', '排序'],
            ])
            ->setTrigger('member_type', '1', 'price_y,price_m,price_a,discount_y,discount_m,discount_a')
            ->setTrigger('member_type', '2', 'prestore,gift_money,discount_pre')    /*  设置触发器*/
            ->fetch();
    }


    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    public function edit($id = 0){

        if ($id === 0) $this->error('参数错误');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            $data['update_time'] = request()->time();
            // 验证
            $data['icon'] = get_file_path($data['icon']);

            $result = $this->validate($data, 'PrivilegeGroup.edit');
            if(true !== $result) $this->error($result);

            // 原配置内容
            if ($config = PrivilegeGroupModel::update($data)) {
                cache('user_group_privilege', null);
                $forward = $this->request->param('_pop') == 1 ? null : cookie('__forward__');
                // 记录行为
                action_log('user_group_privilege_edit', 'Privilege', $config['id'], UID);
                $this->success('编辑成功', $forward, '_parent_reload');
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = PrivilegeGroupModel::get($id);
        // 使用ZBuilder快速创建表单
        // 使用ZBuilder构建表单页面，并将页面标题设置为“添加”
        return ZBuilder::make('form')
            ->addHidden('id')
            ->addFormItems([
                ['text', 'group_name', '分组名称', '<span class="text-danger">必填</span>'],
                ['select', 'member_type', '会员类型','',['1'=>'线上会员','2'=>'线下会员']],
                ['text', 'price_y', '月费价格','<span class="text-danger">必填</span>'],
                ['text', 'price_m', '半年费价格','<span class="text-danger">必填</span>'],
                ['text', 'price_a', '年费价格','<span class="text-danger">必填</span>'],
                ['text', 'prestore', '需预存金额','<span class="text-danger">必填</span>'],
                ['text', 'gift_money', '赠送金额','<span class="text-danger">必填</span>'],
                ['text', 'discount_y', '月费折扣'],
                ['text', 'discount_m', '半年费折扣'],
                ['text', 'discount_a', '年费折扣'],
                ['text', 'discount_pre', '预存制折扣'],
                ['image', 'icon', '会员图标'],
                ['colorpicker', 'usernamecolor', '会员名字颜色'],
                ['text', 'description', '相关描述'],
                ['text', 'sort', '排序'],
            ])
            ->js('privilege_edit')
            ->setTrigger('member_type', '1', 'price_y,price_m,price_a,discount_y,discount_m,discount_a')
            ->setTrigger('member_type', '2', 'prestore,gift_money,discount_pre')    /*  设置触发器*/
            ->setFormData($info)
            ->fetch();

    }

}