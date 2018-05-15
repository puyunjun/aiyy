<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\4\25 0025
 * Time: 17:52
 */

namespace app\broker\model\home;
use think\Model;
use think\Db;
class BrokerUser extends  Model
{

    protected $name='user_broker';


    //个人信息查询
    public function sel_broker_info($ubid){
        $re = $this::name('user_broker')
            ->alias('ub')
            ->join('user_broker_auth uba','uba.id = ub.ubid')
            ->where('ub.ubid',$ubid)
            ->field('uba.phone,ub.ubid,ub.username,ub.last_login_time,ub.email,ub.account,ub.sex')
            ->find();
        return $re;
    }


    //经纪人账户信息查询
    public function sel_broker_account(){
        $re = $this::name('user_broker')
            ->field('alipay_num,alipay_name')
            ->find();
        return $re;
    }



    //查询经纪人当月的收入和支出
    public function broker_withdraw($ubid,$year=2018,$month=01){
        $subsql = Db::table('dp_user_broker_withdraw')
            ->where(['ubid'=>$ubid,'YEAR(FROM_UNIXTIME(create_time))'=>$year,'MONTH(FROM_UNIXTIME(create_time))'=> $month])
            ->field("ubid,sum(withdrawals) as withdrawals,DATE_FORMAT(FROM_UNIXTIME(create_time),'%Y-%m') as month")
            ->group("month")
            ->buildSql();

        $res =  Db::table('dp_broker_sales_record')
            ->alias('a')
            ->join([$subsql=> 'w'], 'a.buid = w.ubid','LEFT')
            ->field("sum(a.get_money) as get_money,w.withdrawals,DATE_FORMAT(FROM_UNIXTIME(create_time),'%Y-%m') as amonth")
            ->where(['buid'=>$ubid,'YEAR(FROM_UNIXTIME(a.create_time))'=>$year,'MONTH(FROM_UNIXTIME(a.create_time))'=> $month])
            ->find();

        return $res;
    }

}