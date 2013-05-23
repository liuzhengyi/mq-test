<?php
/*
 * testdata.php
 * 简单起见，将本应该从数据库获取的数据直接写到这个文件里
 * 仅测试时用
 * liuzhengyi 2013-05-22
 */

// clients
$testdata_clients = array(
    array('id'=>'1', 'token_salt'=>'1234', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client1.php'),
    array('id'=>'2', 'token_salt'=>'2345', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client2.php'),
    array('id'=>'3', 'token_salt'=>'3456', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client3.php'),
    array('id'=>'4', 'token_salt'=>'4567', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client4.php'),
    array('id'=>'5', 'token_salt'=>'5678', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client5.php'),
    array('id'=>'6', 'token_salt'=>'6789', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client6.php'),
    array('id'=>'7', 'token_salt'=>'7890', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client7.php'),
    array('id'=>'8', 'token_salt'=>'8901', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client8.php'),
    array('id'=>'9', 'token_salt'=>'9012', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client9.php'),
    array('id'=>'10', 'token_salt'=>'0123', 'listen_on'=>'http://mq.gipsa.name/damon4/clients/client10.php'),
);

// channels
$now = time();
$testdata_channels = array(
    array('id'=>'1', 'creator'=>'1', 'listener_list'=>'1,2,3,4,5,6,7,8,9,10', 'create_time'=>"$now", 'valide'=>true),
    array('id'=>'2', 'creator'=>'2', 'listener_list'=>'1,2,3,4,5,6,7,8,9,10', 'create_time'=>"$now", 'valide'=>true),
    array('id'=>'3', 'creator'=>'3', 'listener_list'=>'1,2,3,4,5,6,7,8,9,10', 'create_time'=>"$now", 'valide'=>true),
    array('id'=>'4', 'creator'=>'4', 'listener_list'=>'1,2,3,4,5,6,7,8,9,10', 'create_time'=>"$now", 'valide'=>true),
    array('id'=>'5', 'creator'=>'5', 'listener_list'=>'1,2,3,4,5,6,7,8,9,10', 'create_time'=>"$now", 'valide'=>true),
);

// messages
$testdata_messages = array(
    // 1-10
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 11-20
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 21-30
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 31-40
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 41-50
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 51-60
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 61-70
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 71-80
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 81-90
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    // 91-100
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'5', 'channel'=>'5', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'4', 'channel'=>'4', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'3', 'channel'=>'3', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'2', 'channel'=>'2', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),
    array('from'=>'1', 'channel'=>'1', 'birth_time'=>'1334567890', 'body'=>'this is a test message from damon-test4', 'type'=>'str', 'priority'=>'h', 'ttl'=>'0'),

);


foreach($testdata_messages as &$msg) {
    $data = '';
    foreach($msg as $msg_piece) {
        $data .= $msg_piece;
    }
    $token_salt = $testdata_clients[$msg['from']]['token_salt'];
    $token = hash_hmac('sha1', "$data", "$token_salt");
    $msg['token'] = $token;
}
unset($msg);

?>
