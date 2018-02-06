<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\2\5 0005
 * Time: 18:30
 */

/*
 * 会员认证
 * */
namespace app\user\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\user\model\home\Identify As IdentifyModel;
use think\Db;
class Identify extends Admin
{

    public function index(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);

        // 获取查询条件
        $map = $this->getMap();

        //得出满足sql的条件
        $map = $this->map_action($map);

        $order = $this->getOrder() ? $this->getOrder() : 'ui.id desc';
        // 数据列表
        $data_list = Db::name('user_identity')->where($map)
            ->alias('ui')
            ->view('dp_user u','phone,real_name','u.id = ui.uid','LEFT')
            ->field('ui.id,ui.id_card_num,ui.sfz_font_img,
            ui.sfz_back_img,ui.sfz_hand_img,ui.create_time,ui.update_time,ui.status')
            ->order($order)
            ->paginate();

        // 分页数据
        $page = $data_list->render();

        //审核按钮
        $btn_verify = [
            'title' => '审核',
            'icon'  => 'fa fa-fw fa-key',
            'href'  => url('verify_sfz', ['id' => '__id__'])
        ];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setPageTitle('会员列表') // 设置页面标题
            ->setTableName('user') // 设置数据表名
            ->setSearch(['ui.id' => 'ID', 'u.phone' => '用户号码']) // 设置搜索参数
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
                ['real_name', '真实姓名','text'],
                ['id_card_num', '身份证号','text'],
                ['sfz_font_img', '身份证正面照','img_url'],
                ['sfz_back_img', '身份证背面照','img_url'],
                ['sfz_hand_img', '手持身份证正面照','img_url'],
                ['status', '审核状态','callback',function($value){
                    switch ($value){
                        case 1:return '<span style="color: #0a6aa1">审核通过</span>';break;
                        case 2:return '<span style="color: #a92222">审核未通过</span>';break;
                        case 3:return '未审核';break;
                        default : return '不限';
                    }
                }],
                ['create_time', '发布时间','datetime'],
                ['update_time', '修改时间','datetime'],
                ['right_button','操作', 'btn']
            ])
            //
           // ->addFilter('user_group ug.group_name') // 添加标题字段筛选
           // ->addFilter('user_release ur.is_sincerity',['1'=>'交纳',0=>'未交纳']) // 添加标题字段筛选
            ->addOrder('ui.id')
            ->addRightButton('custom', $btn_verify) // 添加审核按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch(); // 渲染页面
    }

    //会员审核详情

    public function verify_sfz($id = 0){

        if(request()->isAjax()){
            $data = request()->post();

            //修改审核状态
            if($data['status'] == 2) $msg = '未通过审核';
            else $msg = '通过审核';
           $re = IdentifyModel::update($data);
           if ($re) return json('操作成功,'.$msg);
           else     return json('操作失败，稍后再试');
        }
        $info = IdentifyModel::get($id);
        return ZBuilder::make('form')
            ->addHidden('id')
            ->setPageTitle('审核身份证')
            ->assign(['name' => json_encode(['sfz_font_img','sfz_back_img','sfz_hand_img'])])
            ->addFormItems([
                ['text','id_card_num', '身份证号码'],
                ['image','sfz_font_img', '身份证正面'],
                ['image','sfz_back_img', '身份证背面'],
                ['image','sfz_hand_img', '手持身份证正面'],
            ])
            ->js('photo')
            ->hideBtn(['submit', 'back'])
            ->addBtn('<button type="button" onclick="check_sfz(\'y\','.$id.')" class="btn btn-default">审核通过</button>')
            ->addBtn('<button type="button" onclick="check_sfz(\'n\','.$id.')" class="btn btn-default">拒绝通过</button>')
            ->setFormData($info)
            ->fetch();
    }

}