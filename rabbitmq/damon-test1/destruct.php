<?php   
/* delete.php
 * 2013-05-07
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
// 删除这些交换机
$exchange_names = array(
	'rmq101ex',
	'rmq201ex'
);
// 删除这些队列
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
 
//删除交换机    
foreach($exchange_names as $exchange_name) {
	$ex = new AMQPExchange($channel);   
	$ex->setName($exchange_name); 
	echo "Exchange Status:".$ex->delete()."\n";   
}
   
//删除队列    
foreach($queue_names as $queue_name) {
	$q = new AMQPQueue($channel); 
	$q->setName($queue_name);   
	echo "Message Total:".$q->delete()."\n";   
}
$conn->disconnect();   
?>
