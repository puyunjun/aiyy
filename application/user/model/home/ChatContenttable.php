<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\13 0013
 * Time: 11:04
 */

/*
 *留言模型，分表模型,数据量大时
 * */
namespace app\user\model\home;
use think\Model;
use think\Request;
use think\Db;
class ChatContenttable extends Model
{

    protected $table;   //分库分表后对应的库表


    public function __construct($date = '',Request $request = null)
    {
        parent::__construct($request);
        //选择数据表
        $this->table = $this->is_exists('dp_chat_content_'.$date);;
    }
    //查询表是否存在,不存在则创建表
    private function is_exists($table_name = ''){
        //拼接创建表结构语句
        $sql = '';
        $result = Db::query("SHOW TABLES LIKE '{$table_name}'");
        //$result = Db::query('call sp_query(8)');
        if(!$result){
           $sql .= "CREATE TABLE if NOT EXISTS $table_name(
                    `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
                    `send_uid` INT(11)  UNSIGNED NOT NULL COMMENT '发送者用户id',
                    `receive_uid` INT(11)  UNSIGNED NOT NULL COMMENT '接收者用户id',
                    `content` VARCHAR (255) NOT NULL DEFAULT ' ' COMMENT '发送内容',
                    `create_time` CHAR(10) NOT NULL COMMENT '留言时间',
                    `read_status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户是否读取,0=>未读，1=>已读' 
          )ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';";
           $re = Db::execute($sql);
           if($re>-1){
               return $table_name;
           }else{
               die('无法建表');
           }
       }else{
           //返回表名
           foreach ($result[0] as $value){
               $return_name = $value;
           }
           var_dump($return_name);
           return $return_name;
       }

    }

    public function sel(){
        //获取年月份
         $date = date('Ym',time());

        $this->is_exists($date);
    }
}