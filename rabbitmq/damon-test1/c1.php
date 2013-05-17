<?php   
//配置信息 
$conn_args = array( 
    'host' => '127.0.0.1',  
    'port' => '5672',  
    'login' => 'rmq101u1',  
    'password' => 'rmq101u1', 
    'vhost'=>'/v1' 
);   
$exchange_name = 'rmq101ex'; //交换机名 
$queue_name = 'rmq101qu1'; //队列名 
$routing_key = (isset($argv[1])) ? ($argv[1]) : ('#'); //路由key 
//$routing_key = 'start.upload.oaaaao';
//$routing_key = 'start.upload.oa';
$routing_key = '*.*.oa';
 
//创建连接和channel 
$conn = new AMQPConnection($conn_args);   
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");   
}   
$channel = new AMQPChannel($conn);   
 
// 生成交换机对象
$ex = new AMQPExchange($channel);   
$ex->setName($exchange_name); 
$ex->setType(AMQP_EX_TYPE_TOPIC);
   
//生成队列对象
$q = new AMQPQueue($channel); 
$q->setName($queue_name);   
 
//绑定交换机与队列，并指定路由键 
//echo 'Queue Bind: '.$q->bind($exchange_name, $routing_key)."\n"; 
 
//阻塞模式接收消息 
echo "Message:\n";   
$count = 0;
while(True){ 
    $q->consume('processMessage');
    //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答  
} 
$conn->disconnect();   
 
/**
 * 消费回调函数
 * 处理消息
 */ 
function processMessage($envelope, $queue) { 
global $count;
    $msg = $envelope->getBody(); 
    echo $msg."[$count]\n"; //处理消息 
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答 
} 
