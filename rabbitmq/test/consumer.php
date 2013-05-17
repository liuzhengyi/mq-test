<?php   
//配置信息 
$conn_args = array( 
//    'host' => '127.0.0.1',  
//    'port' => '5672',  
//    'login' => 'guest',  
//    'login' => 'u1',  
    'login' => 'test_reader',  
//    'password' => 'guest', 
//    'password' => 'u1er', 
    'password' => 'test_read', 
//    'vhost'=>'/' 
    'vhost'=>'/v1' 
);   
$e_name = 'tony-topic_logs'; //交换机名 
$q_name = 'tony-q_linvo'; //队列名 
$routing_key = (isset($argv[1])) ? ($argv[1]) : ('#'); //路由key 
 
//创建连接和channel 
$conn = new AMQPConnection($conn_args);   
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");   
}   
$channel = new AMQPChannel($conn);   
 
//创建交换机    
$ex = new AMQPExchange($channel);   
$ex->setName($e_name); 
$ex->setType(AMQP_EX_TYPE_TOPIC); //topic类型  
//$ex->setFlags(AMQP_DURABLE); //持久化 
//echo "Exchange Status:".$ex->declare()."\n";   
   
//创建队列    
$q = new AMQPQueue($channel); 
$q->setName($q_name);   
$q->setFlags(AMQP_DURABLE); //持久化  
//echo "Message Total:".$q->declare()."\n";   
 
//绑定交换机与队列，并指定路由键 
echo 'Queue Bind: '.$q->bind($e_name, $routing_key)."\n"; 
 
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
