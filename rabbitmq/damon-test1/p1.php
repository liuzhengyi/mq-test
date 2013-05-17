<?php   
/* 
 * p1.php
 * 2013-05-07
 *
 * 生成随机组合类型的消息，随机的类型来自：
 * <status>.<event>.<source>
 * status: start|finish|stop
 * event: edit|upload|update
 * source: web|client|oa
 * 如 start.upload.oa stop.edit.web
 * 然后将这些消息发送到rabbitmq server
 * 
 */
//配置信息 
$conn_args = array( 
    'host' => '127.0.0.1',  
    'port' => '5672',  
    'login' => 'rmq101u1',  
    'password' => 'rmq101u1', 
    'vhost'=>'/v1' 
);   
$e_name = 'rmq101ex'; //交换机名 
$statuses = array('start', 'finish', 'stop');
$events = array('edit', 'upload', 'update');
$sources = array('web', 'client', 'oa');
$routing_keys = array($statuses, $events, $sources);
 
//创建连接和channel 
$conn = new AMQPConnection($conn_args);   
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");   
}   
$channel = new AMQPChannel($conn);   
 
//消息内容 
$message = "message from p1(rmq101u1)";
 
//声明交换机对象    
$ex = new AMQPExchange($channel);   
$ex->setName($e_name);   
$ex->setType(AMQP_EX_TYPE_TOPIC);

//发送消息 
//$channel->startTransaction(); //开始事务  
for($i=0; $i<5; ++$i){ 
	$key = '';
	foreach($routing_keys as $keys) {
		$min = 0; $max = count($keys)-1;
		$key .= $keys[rand($min, $max)];
		$key .= '.';
	}
	$key = trim($key, '.');
	//var_dump($key);
	$msg = $message.$i.'with key('.$key.')';
	if($ex->publish($msg, $key)) {
	echo '$msg='.$msg; echo '   $key='.$key;
		echo 'message published'."\n";
	}
    //echo "Message from rmq101u1 with key $key:".$ex->publish($msg, $key)."($i)\n";  
} 
//$channel->commitTransaction(); //提交事务 
 
$conn->disconnect();
?>
