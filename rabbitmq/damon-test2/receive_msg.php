<?php
/* 2013-5-10
 * liuzhengyi
 * damon-test2/receive_msg.php
 * 系统的前端代理，接受client发来的消息
 * 检查消息合法性
 * 将合法消息入列
 *
 * 当前生成使用固定数组模拟真实的消息，消息数量由可选的$arv[1]确定，默认为100。
 */
/*
 * 大致流程：
 * 获取GET/POST消息
 * 判断合法性
 * 放入队列
 */

// ------step-0---获取GET/POST数据---step-0--------------

// 获取GET/POST消息
// 为方便测试，直接使用如下这个固定数组作为消息来源
if(!empty($argv[1]) && is_numeric($argv[1])) {
	$msg_count = intval($argv[1]);
} else {
	$msg_count = 100;
}
$timestamp = strval(time());
$msg_pieces = array('from'=>'oa', 'token'=>'xbn03xj', 'priority'=>'h', 'tag'=>'hello', 'body'=>'this is a message', 'timestamp'=>$timestamp, );
/*
// 真实环境中，从HTTP代理获取消息
$msg_pieces = array('from'=>'', 'token'=>'', 'priority'=>'', 'tag'=>'', 'body'=>'', 'timestamp'=>'', );
foreach($msg_pieces as $key => $value) {
	if(!isset($_REQUEST[$key])) {
		exit('incomplete message');
	}
	$msg_pieces[$key] = trim($_REQUEST[$key]);
}
*/

// -------step-1----判断消息合法性---step-1---------------

// 判断消息合法性
if(!check_adjust_msg(&$msg_pieces)) {
	exit('illegal msg');
}

// --------step-2------将消息放入消息队列---step-2--------

//rmq配置信息 todo 写入配置文件
/*
$rmq_conn_args = array(
    'host' => '127.0.0.1',
//    'host' => '63.223.118.130',	// 外部 rabbitmq server
    'port' => '5672',
    'login' => 'guest',
    'password' => 'guest',
    'vhost'=>'/'
);
$exchange_name = 'pps_inner_msg'; //交换机名
$k_route = 'pps_inner_msg_route_key'; //路由key
*/
require('conf.d/rmq_writer_config.php');
require('conf.d/rmq_general_config.php');

$time_start = time();
for($i=0; $i < $msg_count; $i++) {

	//创建连接和channel
	$conn = new AMQPConnection($rmq_conn_args);
	if (!$conn->connect()) {
	    die("rmq connect error\n");
	}
	$channel = new AMQPChannel($conn);

	//消息内容
	$serial_msg = serialize($msg_pieces);

	//创建交换机对象
	$ex = new AMQPExchange($channel);
	$ex->setName($exchange_name);

	//发送消息
	$ex->publish($serial_msg, $k_route);

	$conn->disconnect();
}
$time_end = time();
echo $msg_count.' msg(s) send',"\n";
echo 'time used: ',$time_end-$time_start,' second(s)',"\n";

// ---------user-defined-functions---------------

function check_adjust_msg($msg_pieces) {
	// todo : do something check and adjust msg_pieces
	return true;
}
?>
