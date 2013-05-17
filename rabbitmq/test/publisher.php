<?php   
/* 
 * 模拟消息分发系统中的消息产生者
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
    'login' => 'test_writer',  
    'password' => 'test_write', 
    'vhost'=>'/v1' 
);   
$e_name = 'tony-topic_logs'; //交换机名 
$q_name = 'tony-q_linvo'; //无需队列名 
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
$message = "test message";   
 
//创建交换机对象    
$ex = new AMQPExchange($channel);   
$ex->setName($e_name);   
$ex->setType(AMQP_EX_TYPE_TOPIC);

//发送消息 
//$channel->startTransaction(); //开始事务  
for($i=0; $i<500; ++$i){ 
	$key = '';
	foreach($routing_keys as $keys) {
		$min = 0; $max = count($keys)-1;
		$key .= $keys[rand($min, $max)];
		$key .= '.';
	}
	$key = trim($key, '.');
	//var_dump($key);
    echo "Send Message with key $key:".$ex->publish($message.$i.'with key('.$key.')', $key)."($i)\n";  
} 
//$channel->commitTransaction(); //提交事务 
 
$conn->disconnect();
?>
