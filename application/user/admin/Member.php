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
use think\Db;
use think\helper\Hash;
use think\Cache;
class Member extends Admin
{

        public function index($group='web'){
            //web 为网站所有会员
            $list_tab = [
                'web' => ['title' => '网站会员', 'url' => url('index', ['group' => 'web'])],
                'sys' => ['title' => '系统会员', 'url' => url('index', ['group' => 'sys'])],
            ];
            cookie('__forward__', $_SERVER['REQUEST_URI']);

            // 获取查询条件
            $map = $this->getMap();
            if($group === 'sys'){
                //系统会员
                $map['u.sys_id']=0;
                $map['u.member_deadline']=0;
            }
            $order = $this->getOrder() ? $this->getOrder() : 'u.id asc';
            // 数据列表
            $field_str = 'u.id,sys_id,group_id,member_deadline,city_id,
            phone,user_type,nickname,head_img,real_name,sex
            ,birthday,qq,address,height,weight,account,point,is_escort,login_time,login_ip';
            $data_list = UserModel::where($map)
                    ->alias('u')
                    ->view('dp_user_group ug','group_name','ug.id = u.group_id','LEFT')
                    ->view('dp_user_auth ua','status','ua.uid = u.id','LEFT')
                    ->field(trim($field_str))
                    ->order($order)
                    ->paginate();
            // 分页数据
            $page = $data_list->render();

            // 使用ZBuilder快速创建数据表格
            return ZBuilder::make('table')
                ->setPageTitle('会员列表') // 设置页面标题
                ->setTableName('user') // 设置数据表名
                ->setTabNav($list_tab,  $group)
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
                    ['user_type', '用户类型','callback',function($value){
                        switch ($value){
                        case 1:return '推荐';break;
                        case 2:return '认证';break;
                        case 3:return '新人';break;
                        default : return '保密状态';
                        }
                    }],
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
                    ['weight', '体重','callback','urldecode'],
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
                    ['status','用户状态','status'],
                    ['right_button','操作','btn']
                ])
                ->addTopButtons('add') // 批量添加顶部按钮
                ->addFilter('group_id') // 添加标题字段筛选
                ->addOrder('u.id,ug.group_name')
                ->addRightButtons('edit') // 批量添加右侧按钮
                ->addRightButton('enable',['table'=>'user_auth','filed'=>'status']) // 批量添加右侧按钮
                ->addRightButton('disable',['table'=>'user_auth','filed'=>'status']) // 批量添加右侧按钮
                ->setRowList($data_list) // 设置表格数据
                ->setPages($page) // 设置分页数据
                ->fetch(); // 渲染页面
        }


        public function add($group = 'auth'){
            if ($this->request->isPost()) {
                // 表单数据
                $data = $this->request->post();
                // 验证
                $result = $this->validate($data, 'UserAuth.'.$group);
                if(true !== $result) $this->error($result);
                if($group === 'auth'){
                    if($data['identity_type'] === 'mobile'){
                        session('reg_user',$data);  //记录帐号认证session
                        $this->success('请继续完善信息', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["SCRIPT_NAME"].'/user/member/add/group/info.html');
                        exit;
                    }
                }
                //系统添加会员权限无过期时间，无审核，
                $data['create_time'] = request()->time();
                $data['user_type'] = 2 ;        //直接定义为认证用户
                $data['is_bind_phone'] = 1;
                $data['sys_id'] = 0000;
                $data['head_img'] = get_file_path($data['head_img']);
                $data['birthday'] = strtotime($data['birthday']);
                if ($column = UserModel::create($data)) {
                            //添加认证信息
                    $auth =array();
                    $auth['credential'] = Hash::make((string)session('reg_user')['credential']);
                    $auth['identifier'] = session('reg_user')['identifier'];
                    $auth['uid'] = $column['id'];
                    $auth['identity_type'] = 'mobile';
                    $auth['create_time'] = request()->time();
                    $auth['regip'] = get_client_ip(1);

                    $re = Db::name('user_auth')->insert($auth);
                    if(!$re){
                        Db::table('dp_user')->where('id',$column['id'])->delete();
                        session('reg_user',null);
                        $this->error('新增失败');
                    }
                    cache('cms_column_list', null);
                    // 记录行为
                    action_log('column_add', 'cms_column', $column['id'], UID, $data['group_id']);
                    session('reg_user',null);
                    $this->success('新增成功', 'index');
                } else {
                    $this->error('新增失败');
                }
            }


            $list_tab = [
                'auth' => ['title' => '帐号认证', 'url' => url('add', ['group' => 'auth'])],
                'info' => ['title' => '基本信息', 'url' => url('add', ['group' => 'info'])],
            ];

            switch ($group) {
                case 'auth':
                    return ZBuilder::make('form')
                        ->setTabNav($list_tab,  $group)
                        ->addHidden('identity_type','mobile')
                        ->addFormItems([
                            ['text','identifier','帐号信息(手机号)','',session('reg_user') ? session('reg_user')['identifier']:''],
                            ['text','credential','设置登录密码','',session('reg_user') ? session('reg_user')['credential']:''],
                        ])
                        ->fetch();
                    break;
                case 'info':
                    //权限分组
                    $bank_type = Db::name('user_group')->column('id,group_name');
                    return ZBuilder::make('form')
                        ->setTabNav($list_tab,  $group)
                        ->addFormItems([
                            ['select', 'group_id', '添加权限', '<span class="text-danger">必填</span>',$bank_type],
                            ['text','city_id', '所属城市'],
                            ['text','phone', '绑定号码','',session('reg_user')['identifier'],'','readonly'],
                            ['text','nickname', '昵称'],
                            ['text','occupation_id', '职业'],
                            ['image','head_img', '头像','', '', '3072','jpg,png,gif'],
                            ['text','real_name', '真实姓名'],
                            ['select','sex', '性别','',['1'=>'男','2'=>'女']],
                            ['date','birthday', '生日','<span class="text-danger">格式2018-01-01</span>'],
                            ['text','qq', 'QQ号码'],
                            ['text','address', '常驻地址'],
                            ['text','height', '身高'],
                            ['text','weight', '体重'],
                            ['text','account', '账户余额'],
                            ['text','point', '积分点'],
                            ['text','forword', '伴游去向'],
                            ['select','is_escort','是否伴游','',['1'=>'伴游','4'=>'非伴游']],
                        ])
                        ->layout(['city_id' => 6, 'member_type'=>6,'phone' => 6,'occupation_id'=>6,
                            'sex' => 6,'birthday' => 6,'qq' => 6,'address' => 6,'height' => 6,
                            'nickname' => 6, 'real_name' => 6])
                        /*->setTrigger('group_id', '6', 'allow_priview_photo,allow_priview_video,allow_chat')*/
                        ->fetch();
                    break;
            }

        }


    public function edit($id = 0,$group = 'text')
    {
        if ($id === 0) $this->error('参数错误');
        $list_tab = [
            'text' => ['title' => '编辑信息', 'url' => url('edit', ['id' => $id,'group' => 'text'])],
            'photo' => ['title' => '上传照片', 'url' => url('edit', ['id' => $id,'group' => 'photo'])],
            'video' => ['title' => '上传视频', 'url' => url('edit', ['id' => $id,'group' => 'video'])],
        ];

        // 保存数据
        if ($this->request->isPost()) {


            // 表单数据
            $data = $this->request->post();
            //图片编辑部分
            if(isset($data['video_type']) ? $data['video_type']: false){
                //获取上传图片的id
                $data['video_url'] = $data['attach_id']?explode(',',$data['attach_id']):'';
                //判断是否已经记录过id
                foreach ($data['video_url'] as $k=>$v){
                    if(in_array($v,Db::name('user_video')->where('uid',$id)->column('attach_id'))){
                        unset($data['video_url'][$k]);
                    }
                }
                $result_video = $this->validate($data, 'Video.edit');
                if(true !== $result_video) $this->error($result_video);
                $insert_data = array();
                foreach ($data['video_url'] as $k=>$v){
                    list(
                        $insert_data[$k]['uid'],$insert_data[$k]['video_url']
                        ,$insert_data[$k]['upload_ip'],$insert_data[$k]['create_time']
                        ,$insert_data[$k]['attach_id'],$insert_data[$k]['video_type']
                        ) = array (
                        $data['uid'], get_file_path($v),get_client_ip(1),request()->time(),
                        $v,$data['video_type']
                    );
                }
                if (Db::name('user_video')->insertAll($insert_data)) {
                    Cache::clear();
                    // 记录行为
                    $details = '节点ID('.$id.')';
                    action_log('member_edit', '', $id, UID, $details);
                    $this->success('添加成功', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], cookie('__forward__'));
                } else {
                    $this->error('添加失败');
                }

            }
            //基本信息部分
            $result = $this->validate($data, 'UserAuth.edit');
            if(true !== $result) $this->error($result);
            $data['birthday'] = strtotime($data['birthday']);

            $data['head_img'] = get_file_path($data['head_img']);

            if (UserModel::update($data)) {
                Cache::clear();
                // 记录行为
                $details = '节点ID('.$id.')';
                action_log('member_edit', 'user_home_user', $id, UID, $details);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        $res = Db::name('user')->where('id',$id)->field('sys_id,member_deadline')->find();
        if($res['sys_id'] === 0 && $res['member_deadline'] ===0){
            //系统会员
            switch ($group) {
                case 'text':
                    $info = UserModel::get($id);

                    $bank_type = Db::name('user_group')->column('id,group_name');
                    //上传照片按钮
                    /*$btn = [
                        'title' => '添加照片、视频',
                        'target' => '_blank',
                        'href' => url('add') // 此属性仅用于a标签按钮，button按钮不产生作用
                    ];*/
                    return ZBuilder::make('form')
                        ->addHidden('id')
                        ->setTabNav($list_tab,  $group)
                        ->addFormItems([
                            ['select', 'group_id', '添加权限', '<span class="text-danger">必填</span>',$bank_type],
                            ['text','city_id', '所属城市'],
                            ['text','phone', '绑定号码','','','','readonly'],
                            ['text','nickname', '昵称'],
                            ['text','occupation_id', '职业'],
                            ['image','head_img', '头像'],
                            ['text','real_name', '真实姓名'],
                            ['select','sex', '性别','',['1'=>'男','2'=>'女']],
                            ['date','birthday', '生日','<span class="text-danger">格式2018-01-01</span>'],
                            ['text','qq', 'QQ号码'],
                            ['text','address', '常驻地址'],
                            ['text','height', '身高'],
                            ['text','weight', '体重'],
                            ['text','account', '账户余额'],
                            ['text','point', '积分点'],
                            ['text','forword', '伴游去向'],
                            ['select','is_escort','是否伴游','',['1'=>'伴游','4'=>'非伴游']],
                        ])
                        ->js('member_edit')
                        ->layout(['city_id' => 6, 'member_type'=>6,'phone' => 6,'occupation_id'=>6,
                            'sex' => 6,'birthday' => 6,'qq' => 6,'address' => 6,'height' => 6,
                            'nickname' => 6, 'real_name' => 6])
                        //->addButton('test',$btn,'a')
                        ->setFormData($info)
                        ->fetch();
                    break;
                case 'photo':
                    //上传照片
                    $map = [
                        'uid'=>$id,
                        'video_type'=>1
                    ];
                    $info = Db::name('user_video')->where($map)->field('video_url,attach_id')->select();
                    $data_info = array();
                    $data_info['attach_id'] = '';
                     foreach($info as $v){
                        $data_info['attach_id'] .= ','.$v['attach_id'];
                        }
                        $data_info['attach_id'] =isset($data_info['attach_id'])? ltrim($data_info['attach_id'],',') :'';
                    return ZBuilder::make('form')
                        ->addHidden('uid',$id)
                        ->addHidden('video_type',1)
                        ->setTabNav($list_tab,$group)
                        ->addImages('attach_id', '上传照片', '可传多张', '', '3072', 'jpg,png,gif')
                        ->setFormData($data_info)
                        ->js('member_images')
                        ->fetch('',['uid'=>$id]);
                    break;
                case 'video':
                    //上传照片
                    $map = [
                        'uid'=>$id,
                        'video_type'=>2
                        ];
                    $info = Db::name('user_video')->where($map)->field('video_url')->find();
                    return ZBuilder::make('form')
                        ->addHidden('uid',$id)
                        ->addHidden('video_type',2)
                        ->setTabNav($list_tab,$group)
                        ->addVideo('video_url','添加视频')
                        ->hideBtn('submit,back')
                        ->setFormData($info)
                        ->fetch();
                    break;
            }


        //权限分组

       }else{
            $this->error('非系统会员禁止编辑','index');
        }
    }



    //系统会员删除图片
    public function image_delete(){
            if(request()->isAjax()){
                $attach_id= request()->post('attach_id');

                $uid =request()->post('uid');
                //删除对应的图片
                $re = Db::name('user_video')->where(array('uid'=>$uid,'attach_id'=>$attach_id))->delete();

                //删除附件图片，先移除服务文件，再删数据库
                /*$attachment = Db::name('admin_attachment');

                $unlink = @unlink($attachment->where('id',$attach_id)->value('path'));

                $attachment->where('id',$attach_id)->delete();*/
                if($re !== false){
                    return json(array('code'=>200,'msg'=>'删除成功'));
                }else{
                    return json('删除失败，稍后再试');
                }

            }
    }


    //系统会员上传视频
    public function up_gr_video(){
            if(request()->isAjax()){
                $data = $this->request->post();

                //保存数据
                $inser_data =array();
                $inser_data['video_url'] =$data['up_data'];
                $inser_data['uid'] =$data['uid'];
                $inser_data['video_type'] = 2 ;
                $inser_data['upload_ip'] =get_client_ip(1);
                $inser_data['create_time'] =request()->time();
                $inser_data['attach_id'] = 0 ;
                Db::name('user_video')->insert($inser_data);

                return json('上传成功');
            }
    }

    //禁用会员
    public function disable($record = [])
    {
        return parent::disable($record); // TODO: Change the autogenerated stub
    }
}