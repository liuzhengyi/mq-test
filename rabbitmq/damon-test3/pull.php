<?php
/*
 * test file
 */
pull($argv[1], $argv[2]);

function pull($msg_pieces, $address) {
	// todo put the message into cache and db, wait for client's fetch
	$now = date("Y-m-d H:i:s");
	echo "[$now] get the msg [$msg_pieces]. \n";
	$now = date("Y-m-d H:i:s");
	echo "[$now] put the msg [$msg_pieces] into cache and wait for [$address] to fetch it.  \n";
	sleep(3);
	$now = date("Y-m-d H:i:s");
	echo "[$now] put the msg '{$msg_pieces['msg_body']}' into DB after a certain time.  \n";
}

?>
