<?php
/* 2013-05-23
 * liuzhengyi
 * damon-test4/distribute_task.php
 *
 * 从MQ中取消息，调用消费程序处理消息
 * 处理的过程是按照分类分发消息的过程，
 *
 * 使用get_clients_list()从测试数据中获取clients列表
 */
// 包含基本配置文件
require('./basic_config.php');
// 包含测试数据
require('./testdata.php');


// 从MQ中取消息

// MQ配置信息
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
    $q->consume('xmpostMessage');	// todo catch exception
    //$q->consume('xmpostMessage', AMQP_AUTOACK); //自动ACK应答
}
$rmq_conn->disconnect();


// ----------------user-defined-functions---------------------

/**
 * xmpostMessage();
 * 消息消费回调函数
 * 一次处理一条消息
 * 获得消息接收者列表
 * 遍历列表，
 * 将消息发给列表中所有clients
 * 将失败情况记录下来
 */
function xmpostMessage($envelope, $queue) {
	global $msg_count;
	$msg_count++;
    $serial_msg = $envelope->getBody();
    $msg_pieces = unserialize($serial_msg);

    // 输出提示到终端，调试时用
    if(DEBUG) {
	    echo "processing msg ({$msg_pieces['body']}) [$msg_count] \n";
    }

    // 获得应该接收此消息的clients地址列表，列表中包含clientID
    $clients_list = get_clients_list($msg_pieces['channel']);
    if(DEBUG) {
        echo 'debug: ' . count($clients_list) . " users to send for this msg.\n";
    }

    // 整理一批需要用Curl发送的消息
    $curl_msgs = array();
    $results = array();
    $sn = 0;
	foreach($clients_list as $client) {
        $curl_msg['sn'] = $sn;
        $curl_msg['url'] = $client['address'];
        $curl_msg['post_data']['birth_time'] = $msg_pieces['birth_time'];
        $curl_msg['post_data']['body'] = $msg_pieces['body'];
        $curl_msg['post_data']['deal_time'] = strval(time());

        $curl_msg_post_data = '';
        $curl_msg_post_data .= $msg_pieces['birth_time'];
        $curl_msg_post_data .= $msg_pieces['body'];
        $curl_msg_post_data .= $curl_msg['post_data']['deal_time'];
        $curl_msg_token = hash_hmac('sha1', $curl_msg_post_data, $client['token_salt']);

        $curl_msg['post_data']['token'] = $curl_msg_token;

        $curl_msgs[$sn] = $curl_msg;

        $results[$sn] = false;
        $sn++;
	}

    // 使用增强的curl_multi_exec发送这一批消息
    global $conf_curl_timeout;
    global $conf_curl_retry_times;
    for($i = 0; $i < $conf_curl_retry_times; $i++) {
        echo "tray $i \n";
        push_curl_xmpost($curl_msgs, $conf_curl_timeout, &$results );
    }

    // 处理发送失败事件
    foreach($curl_msgs as $curl_msg) {
        if(false === $results[$curl_msg['sn']]) {
            write_db($curl_msg);
        }
    }
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
}


/* get_clients_list($channel)
 *
 * 根据channelID获取一个channle的收听者监听地址以及对应的clientID和token_salt
 * 模拟：从testdata.php中获取原始数据
 */
function get_clients_list($channel_id) {
	// todo: get client list from DB or Cache

	// 以下为模拟行为，原始数据从testdata.php中获取
    // 获取$channel信息 todo read db or cache
    global $testdata_channels;
    $channel = $testdata_channels[$channel_id-1];

    // 获取相关clients信息 todo read db or cache
    $clients = explode(',', $channel['listener_list']);
    global $testdata_clients;

	$clients_list = array();
    foreach($clients as $client) {
        $clients_list[] = array(
            'id'=>"$client",
            'address'=>"{$testdata_clients[$client-1]['listen_on']}",
            'token_salt' => "{$testdata_clients[$client-1]['token_salt']}",
            );
    }

	return $clients_list;
}

function write_db($curl_msg) {
    // todo: write the fail incident into database
    echo 'send msg ['. $curl_msg['post_data']['body'] .'] to ['. $curl_msg['url']."] failed. \n";
    echo 'this function ' . __FUNCTION__ . 'would write the incident into db'."\n";
}

?>
