<?php   
/*************************************
 * Date: 2013/5/06
 *************************************/ 
//配置信息 
$conn_args = array( 
//    'host' => '192.168.1.93',  
    'host' => '127.0.0.1',  
    'port' => '5672',  
    'login' => 'guest',  
    'password' => 'guest', 
    'vhost'=>'/' 
);   
$e_name = 'direct_logs'; //交换机名 
//$q_name = 'q_linvo'; //无需队列名 
$k_route = 'key_1'; //路由key 
 
//创建连接和channel 
$conn = new AMQPConnection($conn_args);   
if (!$conn->connect()) {   
    die("Cannot connect to the broker!\n");   
}   
$channel = new AMQPChannel($conn);   
 
//消息内容 
$message = "TEST MESSAGE! 测试消息！";   
 
//创建交换机对象    
$ex = new AMQPExchange($channel);   
$ex->setName($e_name);   
 
//发送消息 
//$channel->startTransaction(); //开始事务  
for($i=0; $i<50000; ++$i){ 
    echo "Send Message:".$ex->publish($message, $k_route)."\n";  
} 
//$channel->commitTransaction(); //提交事务 
 
$conn->disconnect();   
?>
