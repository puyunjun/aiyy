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
                        ->field('g.member_type,g.discount_y,g.discount_m,g.discount_a,g.discount_pre')
                        ->select();

        //重新构造数据结构
        $new_result = array();
        foreach ($result as $k=>$v){
            $result[$k]['privilege'] =array();
            //构造相应权限
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

            if($result[$k]['privilege'] === array()) continue;

            if ($v['member_type'] === 1){
                //线上会员    重新构造数据结构
                //月费用  对应 月折扣一组

                //月折扣组  删除其他费用及折扣组即可
                $result[$k]['price_y'] =  $result[$k];
                unset($result[$k]['price_y']['price_m']);
                unset($result[$k]['price_y']['price_a']);
                unset($result[$k]['price_y']['discount_m']);
                unset($result[$k]['price_y']['discount_a']);
                //收录月折扣数组
                $new_result[$k]['discount']['price_y']  = $result[$k]['price_y'];


                //半年折扣组  同样删除其他
                $result[$k]['price_m'] =  $result[$k];
                unset($result[$k]['price_m']['price_y']);
                unset($result[$k]['price_m']['price_a']);
                unset($result[$k]['price_m']['discount_y']);
                unset($result[$k]['price_m']['discount_a']);
                //收录半年折扣数组
                $new_result[$k]['discount']['price_m']  = $result[$k]['price_m'];

                //年折扣组  同样删除其他
                $result[$k]['price_a'] =  $result[$k];
                unset($result[$k]['price_a']['price_y']);
                unset($result[$k]['price_a']['price_m']);
                unset($result[$k]['price_a']['discount_y']);
                unset($result[$k]['price_a']['discount_m']);
                //收录年折扣数组
                $new_result[$k]['discount']['price_a'] = $result[$k]['price_a'];

            }


            if($v['member_type'] === 2){
                //收录线下会员组
                $new_result[$k] = $result[$k];
            }
        }
        //var_dump($new_result);exit;
        return $new_result;
    }

}