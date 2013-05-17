<?php   
/* 
 * p3.php
 * 2013-05-07
 *
 * 生成随机组合类型的消息，随机的类型来自：
 * <status>.<event>.<source>
 * status: start|finish|stop
 * event: edit|upload|update
 * source: web|client|oa
 * 如 start.upload.oa stop.edit.web
 * 
 */
//配置信息 
$conn_args = array( 
    'host' => '127.0.0.1',  
    'port' => '5672',  
    'login' => 'rmq201u2',  
    'password' => 'rmq201u2', 
    'vhost'=>'/v1' 
);   
$exchange_name = 'rmq201ex'; //交换机名 
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
$message = "message from p3(rmq201u2)";
 
//声明交换机对象    
$ex = new AMQPExchange($channel);   
$ex->setName($exchange_name);   
//$ex->setType(AMQP_EX_TYPE_TOPIC);

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
    echo "Send $message with key $key:".$ex->publish($message.$i.'with key('.$key.')', $key)."($i)\n";  
} 
//$channel->commitTransaction(); //提交事务 
 
$conn->disconnect();
?>
