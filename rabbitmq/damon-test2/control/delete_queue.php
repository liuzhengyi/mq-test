<?php
/* 2013-05-10
 * liuzhengyi
 * damon-test2/delete_queue.php
 *
 * 从MQ中取消息，调用消费程序处理消息
 * 处理的过程是按照分类分发消息的过程，分发给pull.php或push.php
 */

// 从MQ中取消息

//配置信息
define("DEBUG", true);
//define("DEBUG", false);
$conn_args = array(
    'host' => '127.0.0.1',
//    'host' => '63.223.118.130',
    'port' => '5672',
    'login' => 'guest',
    'password' => 'guest',
    'vhost'=>'/'
);
if(empty($argv[1])) {
	$q_name = 'pps_inner_msg';
} else {
	$q_name = $argv[1];
}

//创建连接和channel
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);

//创建队列
$q = new AMQPQueue($channel);
$q->setName($q_name);
$q->setFlags(AMQP_DURABLE); //持久化
$q->declare();
if(DEBUG) {
	echo "Message Total:".$q->declare()."\n";
}
$q->delete();

$conn->disconnect();

?>
