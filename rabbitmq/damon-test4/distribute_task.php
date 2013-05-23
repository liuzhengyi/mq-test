<?php
/* 2013-05-10
 * liuzhengyi
 * damon-test2/distribute_task.php
 *
 * 从MQ中取消息，调用消费程序处理消息
 * 处理的过程是按照分类分发消息的过程，
 *
 * 使用get_user_list()生成用户，模拟从数据库中获取用户
 * 每次生成用户的数量通过本程序的$curl_msgv[1]指定，默认为10。
 */
// 包含基本配置文件
require('./basic_config.php');

// 接受两个命令行参数指明推送时使用的回调函数和模拟用户的数量
$push_types = array('log', 'post', 'mpost', 'xmpost', 'socket');
if( $argc < 3 || empty($argv[1]) || !is_numeric($argv[2]) || !in_array($argv[1], $push_types)) {
	$err_msg = "Usage: $argv[0] push_type=[log|post|mpost|xmpost|socket] user_count=n \n";
	exit($err_msg);
}
$push_type = strval($argv[1]);	global $push_type;
$user_count = intval($argv[2]); global $user_count;

// 从MQ中取消息

//配置信息
require('conf.d/rmq_reader_config.php');
require('conf.d/rmq_general_config.php');

// 包含curl_multi_post()
require('./include/push_func.php');

//创建连接和channel
$rmq_conn = new AMQPConnection($rmq_conn_args);
$rmq_conn->connect();	// todo catch exception
$channel = new AMQPChannel($rmq_conn);	// todo catch exception

//假定交换机已经由控制程序./control/init.php创建好，现在只需使用
$ex = new AMQPExchange($channel);	// todo catch exception
$ex->setName($exchange_name);

//假定队列已经由控制程序./control/init.php创建好，现在只需使用
$q = new AMQPQueue($channel);		// todo catch exception
$q->setName($q_name);

//阻塞模式接收消息
if(DEBUG) { echo "Message:\n"; }
$msg_count = 0; global $msg_count;

while(True){
	// 从队列中取消息，然后调用消费程序处理消息
    $q->consume('processMessage');	// todo catch exception
    //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
}
$rmq_conn->disconnect();


// ----------------user-defined-functions---------------------

/**
 * 消费回调函数
 * 处理消息
 * 获得消息接收者列表
 * 遍历列表，
 */
function processMessage($envelope, $queue) {
	global $user_count; // 全局变量，用于指示get_user_list 返回用户的数目
	if(empty($user_count) or !is_numeric($user_count)) {
		$user_count = 10;
	}
	global $msg_count;
	$msg_count++;
    $serial_msg = $envelope->getBody();
    $msg_pieces = unserialize($serial_msg);

    // 输出提示到终端，调试时用
    if(DEBUG) {
	    echo "processing msg ({$msg_pieces['body']}) [$msg_count] \n";
    }

    // 获得应该接收此消息的用户列表
    $user_list = get_user_list($msg_pieces, $user_count);
    if(DEBUG) {
        echo 'debug: ' . count($user_list) . " users to send for this msg.\n";
    }

    // 整理一批需要用Curl发送的消息
    $curl_msgs = array();
    $results = array();
    $sn = 0;
	foreach($user_list as $user) {
        $curl_msg['sn'] = $sn;
        $curl_msg['url'] = 'http://dl.gipsa.name/receive_push.php';
        $curl_msg['post_data']['msg'] = 'msg from enchanced curl multi post';
        $curl_msg['post_data']['time'] = strval(time());
        $curl_msgs[$sn] = $curl_msg;
        $results[$sn] = false;
        $sn++;
	}

    // 使用增强的curl_multi_exec发送这一批消息
    global $conf_curl_timeout;
    global $conf_curl_retry_times;
    for($i = 0; $i < $conf_curl_retry_times; $i++) {
        push_curl_xmpost($curl_msgs, $conf_curl_timeout, &$results );
    }

    // 处理发送失败事件
    foreach($curl_msgs as $curl_msg) {
        if(false === $results[$curl_msg['sn']]) {
            write_db($curl_msg);
        }
    }
    var_dump($results); // debug todo del
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
}



function get_user_list($msg_pieces, $user_count) {
	// todo: get user list from DB or Cache

	// 以下为模拟行为，用户数量根据传入参数确定
	if(!is_numeric($user_count)) {
		$err_msg = 'file:[' . __FILE__ . '] function:[' . __FUNCTION__ ."]. error: parameter 2 should be numeric.\n";
		exit($err_msg);
	}
    // 生成user_list，事实上应该从数据库或缓存中取的
	$user_list = array();
	for($i = 0; $i < $user_count; $i++) {
		$user_list[] = array('address'=>'addresstest', 'type'=>'push',);
	}
	return $user_list;
}

function write_db($curl_msg) {
    // todo: write the fail incident into database
    echo 'send msg ['. $curl_msg['post_data']['msg'] .'] to ['. $curl_msg['url']."] failed. \n";
    echo 'this function ' . __FUNCTION__ . 'would write the incident into db'."\n";
}

?>
