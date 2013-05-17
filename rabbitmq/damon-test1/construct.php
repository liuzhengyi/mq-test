<?php   
/* controller.php
 * control exchanges and queues in vhost /v1
 *
 */
//配置信息 
$conn_args = array( 
    'host' => '127.0.0.1',  
    'port' => '5672',
    'login' => 'admin',
    'password' => 'nimda',
    'vhost'=>'/v1'
);   
// 创建这些交换机 type 均为 topic
$exchange_names = array(
	'rmq101ex',
	'rmq201ex'
);
// 创建这些队列，每个用户一个
$queue_names = array(
	'rmq101qu1',
	'rmq201qu1',
	'rmq201qu2'
);
 
//创建连接和channel 
$conn = new AMQPConnection($conn_args);   
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");   
}   
$channel = new AMQPChannel($conn);   
 
//创建交换机    
$exs = array();
foreach($exchange_names as $exchange_name) {
	$ex = new AMQPExchange($channel);   
	$ex->setName($exchange_name); 
	$ex->setType(AMQP_EX_TYPE_TOPIC); //topic类型  
	$ex->setFlags(AMQP_DURABLE); //持久化 
	echo "Exchange Status:".$ex->declare()."\n";   
	$exs[$exchange_name] = $ex;
}
   
//创建队列    
$qs = array();
foreach($queue_names as $queue_name) {
	$q = new AMQPQueue($channel); 
	$q->setName($queue_name);   
	$q->setFlags(AMQP_DURABLE); //持久化  
	echo "Message Total:".$q->declare()."\n";   
	$qs[$queue_name] = $q;
}

// 绑定队列与交换机
// 由于允许多重绑定，所以，不要忽略其他地方的绑定的影响
// 使用 rmc list_bindings -p /v1 查看所有绑定
// 假定admin有一个预定义的绑定，如果client想声明新的绑定则需要想办法将预定义的绑定清除
$qs['rmq101qu1']->bind('rmq101ex', 'start.*.*');
$qs['rmq201qu1']->bind('rmq201ex', 'start.*.*');
$qs['rmq201qu2']->bind('rmq101ex', 'start.*.*');
$qs['rmq201qu2']->bind('rmq201ex', 'start.*.*');

$conn->disconnect();   
?>
