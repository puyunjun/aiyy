<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\16 0016
 * Time: 18:45
 */
/*
 * 个人认证控制器
 * */
namespace app\user\home;
use app\user\model\home\Authentication As AuthenticateModel;
use app\user\model\home\User;
class Authentication extends Common
{

    public function index(){
        //获取用户姓名性别
        $userInfo = User::where('u.id',UID)
                    ->alias('u')
                   ->join('dp_user_identity ui','ui.uid = u.id','LEFT')
                    ->field('ui.status,u.is_bind_phone,u.sex,u.real_name,ui.id_card_num,ui.sfz_font_img,ui.sfz_back_img,ui.sfz_hand_img')
                    ->find();
        if(!$userInfo->real_name || !$userInfo->is_bind_phone){
            $this->redirect('user/index/index',['param_name'=>'Authentication_data'],'302',['Authentication_data'=>'请至少完善真实姓名和手机绑定']);
            exit;
        }
        $this->assign('userInfo',$userInfo);
        return $this->fetch();
    }
    public function upload(){
        return json(array('a'=>1));
    }

    /*
     * 上传证件照方法
     * */
    public function up_authenticate(){
       $data = request()->post();
       $data ['create_time'] = request()->time();
       $data ['uid'] = UID;
       AuthenticateModel::insert($data);
       return json(AuthenticateModel::getLastInsID());
    }

}