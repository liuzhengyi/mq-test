<?php
/* 2013-5-10
 * liuzhengyi
 * damon-test2/receive_msg.php
 * 系统的前端代理，接受client发来的消息
 * 检查消息合法性
 * 将合法消息入列
 *
 * 使用数据来自testdata.php
 * 正常情况下，调用本文件一次应该处理一条消息
 * 这里同样是出于简单考虑，让本文件一次循环处理一批消息
 */

/*
 * 大致流程：
 * step-0 获取GET/POST消息
 * step-1 判断合法性
 * step-2 放入队列
 */

// ------step-0---获取GET/POST数据---step-0--------------

// 获取GET/POST消息
// 简单起见，从testdata.php获取数据
require('./testdata.php');
$messages = $testdata_messages; // init in './testdata.php'
$msg_count = count($messages);
// 真实环境中，从HTTP代理获取消息
/*
$msg_pieces = array('from'=>'', 'token'=>'', 'priority'=>'', 'tag'=>'', 'body'=>'', 'timestamp'=>'', );
foreach($msg_pieces as $key => $value) {
    if(!isset($_REQUEST[$key])) {
        exit("incomplete message\n");
    }
    $msg_pieces[$key] = trim($_REQUEST[$key]);
}
*/

$time_start = time();
// 循环处理一批消息
foreach($messages as $msg_pieces) {
// -------step-1----判断消息合法性---step-1---------------

// 判断消息合法性并对消息进行适当修改调整 此函数暂时留空，这是个重要函数 todo
if(!check_adjust_msg($msg_pieces, $testdata_clients[$msg_pieces['from']]['token_salt'])) {
    echo("illegal msg\n");
} else {
    echo "legal msg\n";
}

// --------step-2------将消息放入消息队列---step-2--------

//rmq配置信息
require('conf.d/rmq_writer_config.php');
require('conf.d/rmq_general_config.php');


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

    //将消息发送至交换机
    $ex->publish($serial_msg, $k_route);

    $conn->disconnect();
}
$time_end = time();
echo $msg_count.' msg(s) enqueue',"\n";
echo 'time used: ',$time_end-$time_start,' second(s)',"\n";

// ---------user-defined-functions---------------

/* 对消息进行验证，如有必要可做适当调整
 *
 */
function check_adjust_msg(&$msg_pieces, $token_salt) {
    // todo : do something check and adjust msg_pieces
    $data = '';
    foreach($msg_pieces as $k => $v) {
        if('token' != $k) {
            $data .= $v;
        }
    }
    $token = hash_hmac('sha1', "$data", "$token_salt");
    if($token === $msg_pieces['token']) {
        return true;
    }
    return false;
}
?>
