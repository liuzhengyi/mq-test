<?php
/* 2013-05-10
 * liuzhengyi
 * damon-test2/delete_exchange.php
 */


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
if(!empty($argv[1])) {
	$exchange_name = $argv[1];
} else {
	$exchange_name = 'pps_inner_msg'; //交换机名
}
$q_name = 'pps_inner_msg'; //队列名
$k_route = 'pps_inner_msg_route_key'; //路由key

//创建连接和channel
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);

//创建交换机
$ex = new AMQPExchange($channel);
$ex->setName($exchange_name);
$ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
$ex->setFlags(AMQP_DURABLE); //持久化
$ex->declare();
if(DEBUG) {
	echo "Exchange Status:".$ex->declare()."\n";
}

$ex->delete();

$conn->disconnect();

?>
