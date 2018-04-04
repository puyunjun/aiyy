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
use app\user\admin\IdentifyVerify;
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

            //获取到审核的主键id
            $user_info = Db::name('user_identity')->alias('i')
                        ->where('i.id',$data['id'])
                        ->join('__USER__ u','u.id = i.uid','LEFT')
                        ->field('i.id,i.id_card_num,i.sfz_font_img,i.sfz_back_img,i.sfz_hand_img,u.real_name')
                        ->find();
            //点击审核，通过审核接口返回审核结果
            $identify = IdentifyVerify::getinstance();
            //获取需要识别的图片
            $img_data = array();
            $img_data[0]['img_url'] = $user_info['sfz_font_img']; //正面照
            $img_data[0]['forword'] = 'front'; //正面标识
            $img_data[1]['img_url'] = $user_info['sfz_back_img']; //背面照
            $img_data[1]['forword'] = 'back'; //背面标识
            //$res = $identify->export_init('http://aiyueyoo.oss-cn-shenzhen.aliyuncs.com/authentication/2018-3-5/15202226282400.png');
            $code_msg = true;
            foreach ($img_data as $v){
                $res = $identify->export_init($v['img_url'],$v['forword']);
                if($v['forword'] === 'front'){
                    if($res['error_code'] !== 0){
                        $code_msg = '无法识别的身份证';
                        break;
                    }
                    if($res['result']['realname'] !== $user_info['real_name']){
                        //真实姓名不匹配
                        $code_msg = '真实姓名与填写资料不匹配';
                        break;
                    }
                    if($res['result']['idcard'] !== $user_info['id_card_num']){
                        //身份证号码不匹配
                        $code_msg = '身份证号码不匹配';
                        break;
                    }
                }else{
                    if($res['error_code'] !== 0){
                        $code_msg = '无法识别的身份证';
                        break;
                    }
                }

            }
            //修改审核状态
            if($code_msg!==true){
            //审核失败
                $data['status'] = 2; //审核状态正常
                $msg = $code_msg;
            }else{
                $data['status'] = 1; //审核状态正常
                $msg = '通过审核';
            }

           $re = IdentifyModel::update($data);
           if ($re) return json('审核完成,'.$msg);
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
            ->addBtn('<button type="button" onclick="check_sfz('.$id.')" class="btn btn-default">智能审核</button>')
            ->setFormData($info)
            ->fetch();
    }

}