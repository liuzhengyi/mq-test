<?php   
//配置信息 
$conn_args = array( 
    'host' => '127.0.0.1',  
    'port' => '5672',  
    'login' => 'rmq201u1',  
    'password' => 'rmq201u1', 
    'vhost'=>'/v1' 
);   
$exchange_names = array(
	'rmq201ex',
);
$queue_name = 'rmq201qu1'; //队列名 
$routing_key = (isset($argv[1])) ? ($argv[1]) : ('#'); //路由key 
 
//创建连接和channel 
$conn = new AMQPConnection($conn_args);   
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");   
}   
$channel = new AMQPChannel($conn);   

//声明队列    
$q = new AMQPQueue($channel); 
$q->setName($queue_name);   
 
// 声明交换机    
foreach($exchange_names as $exchange_name) {
	$ex = new AMQPExchange($channel);   
	$ex->setName($exchange_name); 
	//绑定交换机与队列，并指定路由键 
//	echo 'Queue Bind: '.$q->bind($exchange_name, $routing_key)."\n"; 
}
   
//$q->setFlags(AMQP_DURABLE); //持久化  
//echo "Message Total:".$q->declare()."\n";   
 
 
//阻塞模式接收消息 
echo "Message:\n";   
$count = 0;
while(True){ 
    $q->consume('processMessage');   
    //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答  
    //echo "message count[$count++]";
} 
$conn->disconnect();   
 
/**
 * 消费回调函数
 * 处理消息
 */ 
function processMessage($envelope, $queue) { 
    $msg = $envelope->getBody(); 
    echo $msg."\n"; //处理消息 
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答 
} 
