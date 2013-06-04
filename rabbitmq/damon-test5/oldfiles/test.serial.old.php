<?php
/*
 * test.php
 * 对本damon中的文件进行包装测试
 * 接受两个命令行参数：
 * @argv[1] -- 消息的条数
 * @argv[2] -- 消息的接收用户数 // 暂不可用
 *
 */
if(isset($argv[1]) && is_numeric($argv[1])) {
	$task_count = $argv[1];
} else {
	$task_count = 9;
}

if(isset($argv[2]) && is_numeric($argc[2])) {
	$user_count = $argv[2];
} else {
	$user_count = 9;
}

$time_start = time();
// 生成消息
for($i = 0; $i < $task_count; $i++) {
	pclose(popen("php receive_msg.php", 'r'));
}

$time_end = time();
$time_used = $time_end - $time_start;

echo "$task_count messages in queue \n";
echo "time used: $time_used second(s)\n";

?>
