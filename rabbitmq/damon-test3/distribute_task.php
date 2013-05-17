<?php
/* 2013-05-10
 * liuzhengyi
 * damon-test2/distribute_task.php
 *
 * 从MQ中取消息，调用消费程序处理消息
 * 处理的过程是按照分类分发消息的过程，分发给pull.php或push.php
 */

// 从MQ中取消息

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
$exchange_name = 'pps_inner_msg'; //交换机名
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

//创建队列
$q = new AMQPQueue($channel);
$q->setName($q_name);
$q->setFlags(AMQP_DURABLE); //持久化
$q->declare();
if(DEBUG) {
	echo "Message Total:".$q->declare()."\n";
}

//绑定交换机与队列，并指定路由键
$q->bind($exchange_name, $k_route);
if(DEBUG) {
	echo 'Queue Bind: '.$q->bind($exchange_name, $k_route)."\n";
}

//阻塞模式接收消息
if(DEBUG) {
	echo "Message:\n";
}
$count = 0;
while(True){
	// 从队列中取消息，然后调用消费程序处理消息
    sleep(1);
    $q->consume('processMessage');
    //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
}
$conn->disconnect();

// ----------------user-defined-functions---------------------

/**
 * 消费回调函数
 * 处理消息
 * 获得消息接收者列表
 * 遍历列表，根据client注册的获取消息方式分别将任务分发给pull.php或push.php
 */
function processMessage($envelope, $queue) {
	global $count;
	$count++;
    $serial_msg = $envelope->getBody();
    $msg_pieces = unserialize($serial_msg);

    // 输出提示到终端，调试时用
    if(DEBUG) {
	    echo "processing msg ({$msg_pieces['body']}) [$count] \n";
    }

    // 获得应该接收此消息的用户列表
    $user_list = get_user_list($msg_pieces);
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
    foreach($user_list as $user) {
    	if('push' == $user['type']) {
		// 使用外部程序 ./push.php 完成push功能
		pclose(popen("php push.php '{$msg_pieces['body']}' '{$user['address']}' >> push.log &", 'r'));
	} else if('pull' == $user['type']) {
		// 使用外部程序 ./pull.php 完成pull功能
		pclose(popen("php pull.php '$msg_pieces' '{$user['address']}' >> pull.log &", 'r'));
	} else {
		exit('error type in user_list');
	}
    }
}

function get_user_list($msg_pieces) {
	// todo: get user list from DB or Cache
	$user_list = array(
		array('address'=>'address0', 'type'=>'pull'),
		array('address'=>'address1', 'type'=>'push'),
		array('address'=>'address2', 'type'=>'push'),
		array('address'=>'address3', 'type'=>'push'),
		array('address'=>'address4', 'type'=>'pull'),
		array('address'=>'address5', 'type'=>'push'),
		array('address'=>'address6', 'type'=>'pull'),
		array('address'=>'address7', 'type'=>'push'),
		array('address'=>'address8', 'type'=>'pull'),
		array('address'=>'address9', 'type'=>'push'),
	);
	return $user_list;
}

?>
