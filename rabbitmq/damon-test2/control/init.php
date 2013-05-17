<?php
/* 2013-05-10
 * liuzhengyi
 * damon-test2/init_queue.php
 *
 * 初始化队列和交换机
 */
define("DEBUG", true);
//define("DEBUG", false);

// 从MQ中取消息

//配置信息
require('../conf.d/rmq_admin_config.php');
require('../conf.d/rmq_general_config.php');

//创建连接和channel
$conn = new AMQPConnection($rmq_conn_args);
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);

//创建交换机
$ex = new AMQPExchange($channel);
$ex->setName($exchange_name);
$ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
$ex->setFlags(AMQP_DURABLE); //持久化
$result = $ex->declare();
if(DEBUG) {
	echo "Exchange Status: $result"." .\n";
}

//创建队列
$q = new AMQPQueue($channel);
$q->setName($q_name);
$q->setFlags(AMQP_DURABLE); //持久化
$result = $q->declare();
if(DEBUG) {
	echo "Message Total: "."$result .\n";
}

//绑定交换机与队列，并指定路由键
$result = $q->bind($exchange_name, $k_route);
if(DEBUG) {
	echo 'Queue Bind: '."$result .\n";
}

$conn->disconnect();

?>
