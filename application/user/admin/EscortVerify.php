<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\4\2 0002
 * Time: 11:01
 */
/*
 * 伴游认证控制器
 * */
namespace app\user\admin;
use app\admin\controller\Admin;
use app\user\model\home\EscortVerify as EscortModel;
use app\common\builder\ZBuilder;
use app\index\controller\Aliyun;
use think\Db;
class EscortVerify extends Admin
{

    public function index(){

        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

        //得出满足sql的条件

        $map = $this->map_action($map);

        $data_list = EscortModel::where($map)
                    ->alias('e')
                    ->view('dp_user U','nickname,head_img,group_id,phone,is_bind_phone','U.id = e.uid')
                    ->view('dp_user_identity di','id_card_num,status as dstatus','di.uid = U.id','LEFT')
                    ->view('dp_user_group dug','group_name','dug.id = U.group_id','LEFT')
                    ->field('e.create_time,e.id,e.status')
                    ->paginate();

        $page = $data_list->render();

        $btn_verify = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-key',
            'href'  => url('verify_escort', ['id' => '__id__'])
        ];
       return ZBuilder::make('table')
            ->setPageTitle('申请伴游列表') // 设置页面标题
            ->setTableName('escort_user') // 设置数据表名
            ->setSearch(['e.id' => 'ID', 'U.phone' => '用户号码']) // 设置搜索参数
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
                ['nickname', '用户昵称','callback','urldecode'],
                ['group_name', '用户会员组','callback',function($val){
                    if($val){
                        return $val;
                    }else{
                        return '非vip会员';
                    }
                }],
                ['head_img', '用户头像','img_url'],
                ['dstatus', '身份认证审核状态','callback',function($value){
                    switch ($value){
                        case 1:return '<span style="color: #0a6aa1">审核通过</span>';break;
                        case 2:return '<span style="color: #a92222">审核未通过</span>';break;
                        case 3:return '未审核';break;
                        default : return '不限';
                    }
                }],
               ['is_bind_phone', '是否绑定手机','callback',function($value){
                   switch ($value){
                       case 1:return '<span style="color: #0a6aa1">已绑定</span>';break;
                       case 2:return '<span style="color: #a92222">未绑定</span>';break;
                       default : return '未绑定';
                   }
               }],
                ['create_time', '申请时间','datetime'],
                ['status', '伴游审核状态','callback',function($value){
                    switch ($value){
                        case 1:return '<span style="color: #0a6aa1">审核通过</span>';break;
                        case 0:return '<span style="color: #f1a417">未通过</span>';break;
                        case 2:return '<span style="color: #a92222">未审核</span>';break;
                    }
                }],
                ['right_button','操作', 'btn']
            ])
            ->addOrder('e.id')
            ->addFilter('user_group dug.group_name') // 添加标题字段筛选
             ->addRightButton('custom', $btn_verify) // 添加审核按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面

    }


    //审核伴游按键
    public function verify_escort($id=0){
        if(request()->isAjax()){
                //伴游审核表主键id
                $id = request()->post('id');

                //审核结果
            $status = request()->post('status');

            if($status === 'y'){
                $code = 1;
                $sys_news_content = '您已成为平台伴游，请遵守平台伴游协议，祝您生活愉快';
            }else{
                $code = 0;
                $sys_news_content = '伴游审核未通过,请联系管理员,重新提交申请';
            }
                //获取 uid

            $uid = EscortModel::where('id',$id)->value('uid');
            $user_info = Db::table('dp_user')
                ->where('id',$uid)
                ->field('phone,real_name')
                ->find();
            if($code === 1){
                //发送短信给用户
                $aliyun = Aliyun::getinstance();
                //查询用户的真实姓名和电话
                $response = $aliyun->sendSms(
                    "爱约游", // 短信签名
                    "SMS_128875588", // 短信模板编号
                    $user_info['phone'], // 短信接收者
                    Array(  // 短信模板中字段的值
                        "name" =>  $user_info['real_name'],
                        'by'=>'伴游'
                    ),
                    time()   // 流水号,选填
                );
            }
            //修改数据库
            Db::startTrans();
            try{
                Db::table('dp_user')->where('id',$uid)->setField('is_escort',$code);
                Db::name('escort_user')->where('id',$id)->update(array('status'=>$code,'verify_time'=>request()->time()));
                //发送信息给用户
                $data_sys_new = array(
                    'uid'=>$uid,
                    'sys_news_create_time'=>request()->time(),
                    'sys_news_content'=>$sys_news_content,
                );
                Db::name('member_sys_news')->insert($data_sys_new);
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }

        }
        $info = EscortModel::where('e.id='.$id)
                ->alias('e')
                ->view('dp_user U','nickname,forword,head_img,group_id,phone,is_bind_phone','U.id = e.uid')
                ->view('dp_user_identity di','id_card_num,status as dstatus','di.uid = U.id','LEFT')
                ->view('dp_user_group dug','group_name','dug.id = U.group_id','LEFT')
                ->field('e.create_time,e.id,e.status')
                ->find();
        return ZBuilder::make('form')
            ->addHidden('id')
            ->setPageTitle('审核身份证')
            ->addFormItems([
                ['text','id_card_num', '身份证号码'],
                ['image','head_img', '头像'],
                ['text','group_name', '会员组名称'],
                ['text','forword', '伴游去向'],
            ])
            ->js('escort_verify')
            ->hideBtn(['submit', 'back'])
            ->addBtn('<button type="button" onclick="check_sfz('.$id.',\'y\')" class="btn btn-default">审核通过</button>')
            ->addBtn('<button type="button" onclick="check_sfz('.$id.',\'n\')" class="btn btn-default">审核不通过</button>')
            ->setFormData($info)
            ->fetch();
    }

}