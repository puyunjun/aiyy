<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\3 0003
 * Time: 11:42
 */

namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\Privilege As PrivilegeModel;
use app\user\model\home\PrivilegeGroup;
use think\Db;
class Privilege extends Admin
{

    public function group(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();
        $map = $this->map_action($map);
        $order = $this->getOrder();
        if(!$order) $order = 'ugp.id asc';
        // 数据列表
        $field_str = 'id,group_id,allow_priview_list,
        allow_priview_photo,allow_priview_video,
        allow_chat,allow_insurance,allow_recommend,
        allow_videoconferencing,allow_escort_recommend,allow_date,create_time,update_time';
        $data_list = Db::view('dp_user_group ug','id,group_name')
            ->where($map)
            ->view('dp_user_group_privilege ugp',trim($field_str),'ugp.group_id = ug.id','INNER')
            ->order($order)
            ->paginate();

        // 分页数据
        $page = $data_list->render();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('分组列表') // 设置页面标题
            ->setTableName('user_group_privilege') // 设置数据表名
            ->setSearch(['dp_user_group_privilege.id' => 'ID', 'dp_user_group.group_name' => '会员组']) // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id','ID'],
                ['group_name','会员组名称','text'],
                ['allow_priview_photo','允许查看照片','switch'],
                ['allow_priview_video','允许查看视频','switch'],
                ['allow_chat','允许私聊','switch'],
                ['allow_insurance','是否享受保险','switch'],
                ['allow_recommend','是否享受客服推荐','switch'],
                ['allow_videoconferencing','允许真人视频','switch'],
                ['allow_date','允许真人见面','switch'],
                ['create_time','添加时间','datetime'],
                ['update_time','编辑时间','datetime'],
                ['right_button','操作', 'btn']
            ])
            ->addTopButtons('add,delete') // 批量添加顶部按钮
            ->addFilter('user_group ug.group_name') // 添加标题字段筛选
            ->addOrder('ugp.id,ug.group_name,ugp.create_time,ugp.update_time')
            ->addRightButtons('edit,delete') // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }


    public function add(){
// 保存数据
        if ($this->request->isPost()) {
            // 表单数据

            $data = $this->request->post();

            $data['create_time'] = request()->time();
           foreach ($data as $k=>$v){
               if($v === 'on'){
                   $data[$k] = 1;
               }
           }
            // 验证
            $result = $this->validate($data, 'Privilege');
            if(true !== $result) $this->error($result);

            if ($column = PrivilegeModel::create($data)) {
                cache('cms_column_list', null);
                // 记录行为
                action_log('column_add', 'cms_column', $column['id'], UID, $data['group_id']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }
        //权限分组
        $bank_type = Db::name('user_group')->column('id,group_name');
        // 使用ZBuilder构建表单页面，并将页面标题设置为“添加”
        return ZBuilder::make('form')
            ->addFormItems([
                ['select', 'group_id', '分组名称', '<span class="text-danger">必填</span>',$bank_type],
                ['switch', 'allow_priview_photo', '是否允许查看照片'],
                ['switch', 'allow_priview_video', '是否允许查看视频'],
                ['switch', 'allow_chat', '是否允许私聊'],
                ['switch', 'allow_insurance', '是否享受保险'],
                ['switch', 'allow_recommend', '是否享受客服推荐'],
                ['switch', 'allow_videoconferencing', '是否允许真人视频'],
                ['switch', 'allow_escort_recommend', '是否享受高级伴游推荐'],
                ['switch', 'allow_date', '是否允许真人见面'],
            ])
            /*->setTrigger('group_id', '6', 'allow_priview_photo,allow_priview_video,allow_chat')*/
            ->fetch();
    }

    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }


    //编辑

    public function edit($id = 0)
    {
        if ($id === 0) $this->error('参数错误');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data['allow_priview_photo'] = 0;
            $data['allow_priview_video'] = 0;
            $data['allow_chat'] = 0;
            $data['allow_insurance'] = 0;
            $data['allow_videoconferencing'] = 0;
            $data['allow_escort_recommend'] = 0;
            $data['allow_date'] = 0;
            //$data = $this->request->post();
            $data['update_time'] = request()->time();
            $data['group_id'] = request()->post('group_id');
            $data['id'] = request()->post('id');
            $post_data = $this->request->post();
            foreach ($post_data as $k=>$v){
                if($v === 'on'){
                    $data[$k] = 1;
                }
                //如果没有该id下的字段权限全部为0
            }

            // 验证
            $result = $this->validate($data, 'Privilege.edit');
            if(true !== $result) $this->error($result);


            // 原配置内容

            if ($config = PrivilegeModel::update($data)) {
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
        $info = PrivilegeModel::get($id);
        //权限分组
        $bank_type = Db::name('user_group')->column('id,group_name');
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('分组权限编辑')
            ->addHidden('id')
            ->addSelect('group_id', '分组名称', '', $bank_type)
            ->addFormItems([
                ['switch', 'allow_priview_photo', '是否允许查看照片'],
                ['switch', 'allow_priview_video', '是否允许查看视频'],
                ['switch', 'allow_chat', '是否允许私聊'],
                ['switch', 'allow_insurance', '是否享受保险'],
                ['switch', 'allow_recommend', '是否享受客服推荐'],
                ['switch', 'allow_videoconferencing', '是否允许真人视频'],
                ['switch', 'allow_escort_recommend', '是否享受高级伴游推荐'],
                ['switch', 'allow_date', '是否允许真人见面'],
            ])
            ->setFormData($info)
            ->fetch();
    }
}