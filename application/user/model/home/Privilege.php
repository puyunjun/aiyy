<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\2 0002
 * Time: 18:04
 */

namespace app\user\model\home;
use app\user\model\home\User As UserModel;
use think\Model;
class Privilege extends Model
{


    protected  $data;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->data = UserModel::user_privilege();
    }

    public function sel_privilege(){

        //用户权限信息
        $allow_priview_data =array(
            'allow_priview_photo'=>$this->data->allow_priview_photo,
            'allow_priview_video'=>$this->data->allow_priview_video,
            'allow_chat'=>$this->data->allow_chat,
        );
        return (object)$allow_priview_data;
    }

}