<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\1\10 0010
 * Time: 14:41
 */
/*
 * 用户分组模型
 *
 * */
namespace app\user\model\home;
use think\Model;
use think\Db;
class PrivilegeGroup extends Model
{

    protected $name = 'user_group';

    public function __construct($data = [])
    {
        parent::__construct($data);

    }

    //查询需要付费的会员组
    public function sel_need_fee(){
        $result  =   Db::name('user_group')
                        ->alias('g')
                        ->where('g.price_y','>','0.00')
                        ->whereOr('g.prestore','>','0.00')
                        ->join('dp_user_group_privilege gp','g.id=gp.group_id','LEFT')
                        ->field('g.id,g.group_name,g.price_y,g.price_m,g.price_a,g.prestore,g.gift_money,g.icon')
                        ->field('g.usernamecolor,gp.allow_priview_photo,gp.allow_priview_video')
                        ->field('gp.allow_chat,gp.allow_insurance,gp.allow_recommend,gp.allow_videoconferencing,gp.allow_escort_recommend,gp.allow_date')
                        ->select();
        foreach ($result as $k=>$v){
            if ($v['allow_insurance'] == 1) $result[$k]['privilege'][] = '享受客服保险';
            else unset($result[$k]['allow_insurance']);
            if ($v['allow_priview_photo'] == 1) $result[$k]['privilege'][] = '查看照片';
            else unset($result[$k]['allow_priview_photo']);
            if ($v['allow_priview_video'] == 1) $result[$k]['privilege'][] = '查看视频';
            else unset($result[$k]['allow_priview_video']);
            if ($v['allow_chat'] == 1) $result[$k]['privilege'][] = '私聊(约TA)';
            else unset($result[$k]['allow_chat']);
            if ($v['allow_recommend']  == 1) $result[$k]['privilege'][]  = '享受客服推荐';
            else unset($result[$k]['allow_recommend']);
            if ($v['allow_videoconferencing']  == 1) $result[$k]['privilege'][] = '真人视频';
            else unset($result[$k]['allow_videoconferencing']);
            if ($v['allow_escort_recommend'] == 1) $result[$k]['privilege'][] = '享受客服高级认证推荐';
            else unset($result[$k]['allow_escort_recommend']);
            if ($v['allow_date'] == 1) $result[$k]['privilege'][] = '真人见面';
            else unset($result[$k]['allow_date']);
        }
        return $result;
    }

}