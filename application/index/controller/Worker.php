<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\3\30 0030
 * Time: 11:24
 */

namespace app\index\controller;

use think\worker\Server;
use think\db;
class Worker extends Server
{
    protected $socket = 'websocket://dev.dophin.local:2346';

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {
        $msg_arr = array(
            'send_uid'=>1,
            'receive_uid'=>2,
            'content'=>$data,
            'create_time'=>request()->time(),
            'read_status'=>0,
            'chat_sign'=>'1-2'
        );
        Db::name('chat_content_date')->insert($msg_arr);
        $connection->send($data);
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {

    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

    }
}
