<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\5\10 0010
 * Time: 18:08
 */

namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\Db;
class BrokerUser extends  Admin
{

    //查询经纪人注册情况

    public function index(){
        //
        $map = $this->getMap();
        $data = Db::name('user_broker')
            ->alias('ub')
            ->where($map)
            ->join('dp_user_broker_auth uba','uba.id = ub.ubid')
            ->field('ub.id,ub.account,ub.code_img,ub.alipay_num,ub.username,ub.sex,ub.last_login_ip,uba.phone')
            ->paginate();
        $page = $data->render();
        return ZBuilder::make('table')
            ->setPageTitle('经纪人列表') // 设置页面标题
            ->setTableName('user_broker') // 设置数据表名
            ->setSearch('ub.username','昵称') // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['phone', '电话号码','text'],
                ['username', '用户名','text'],
                ['sex', '性别','callback',function($data){
                    switch ($data){
                        case 1:return '男';
                            break;
                        case 0:return '女';
                            break;
                        default : return '保密';
                    }
                }],
                ['account', '账户余额','text'],
                ['alipay_num', '支付宝账户','text'],
                ['last_login_ip', '最后登陆ip(v4)地址','callback',function($data){return long2ip($data);}],
            ])
            ->setRowList($data) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面

    }

    //经纪人提现列表
    public  function withdraw(){
        //
        $map = $this->getMap();
        $data = Db::name('user_broker_withdraw')
            ->alias('uw')
            ->where($map)
            ->join('dp_user_broker ub','ub.ubid = uw.ubid','LEFT')
            ->join('dp_user_broker_auth uba','uba.id = ub.ubid','LEFT')
            ->field('uw.id,ub.account,ub.alipay_num,ub.alipay_name,uba.phone,uw.withdrawals,uw.create_time,uw.status')
            ->paginate();
        $page = $data->render();
        return ZBuilder::make('table')
            ->setPageTitle('经纪人列表') // 设置页面标题
            ->setTableName('user_broker_withdraw') // 设置数据表名
            ->setSearch('ub.username','昵称') // 设置搜索参数
            ->addColumns([ // 批量添加列
                ['id', 'ID'],
                ['phone', '电话号码','text'],
                ['account', '账户余额','text'],
                ['alipay_num', '支付宝账户','text'],
                ['alipay_name', '支付宝名称','text'],
                ['withdrawals', '提现金额','text'],
                ['create_time', '提现时间','datetime'],
                ['status', '申请状态','callback',function($data){
                    switch ($data){
                        case 0:return '申请中';
                            break;
                        case 1:return '提现成功';
                            break;
                    }
                }],
            ])
            ->setRowList($data) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->addColumn('right_button', '操作', 'btn')
            ->addRightButtons(['edit'=> ['title' => '查看详情']]) // 批量添加右侧按钮
            ->fetch(); // 渲染页面
    }

    public function edit($id = '')
    {
        //
        $data = Db::name('user_broker_withdraw')
            ->alias('uw')
            ->where('uw.id='.$id)
            ->join('dp_user_broker ub','ub.ubid = uw.ubid','LEFT')
            ->join('dp_user_broker_auth uba','uba.id = ub.ubid','LEFT')
            ->field('uw.id,ub.account,ub.alipay_num,ub.alipay_name,uba.phone,uw.withdrawals,uw.create_time,uw.status')
            ->find();
        //var_dump($admin_chat_user);exit;
        return ZBuilder::make('form')
            ->addHidden('uid',$id)
            ->addFormItems([
                ['text','account', '账户余额'],
                ['text','alipay_num', '支付宝帐号',],
                ['text','alipay_name', '支付宝名字',],
                ['date','create_time', '申请提现时间'],
            ])
            ->setFormData($data)
            ->js('withdraw')
            ->hideBtn('submit,back')
            ->addBtn('<button type="button" onclick="check_withdraw('.$id.')" class="btn btn-default">确认用户提现</button>')
            ->fetch();

    }

    public function withdraw_check(){
        if(request()->isAjax()){
            $id = request()->post('id');

           $re = Db::name('user_broker_withdraw')->where('id',$id)->setField('status',1);

           return json(array('code'=>200,'msg'=>'确认成功'));
        }
    }

}