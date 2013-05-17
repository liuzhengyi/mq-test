<?php
push($argv[1], $argv[2]);

function push($msg_body, $address) {
	// todo push the message to client, if cannot, write it to db and record this incident in cache
	$now = date("Y-m-d H:i:s");
	echo "[$now] get the msg [$msg_body]. \n";
	$now = date("Y-m-d H:i:s");
	echo "[$now] trying to push msg [$msg_body] to [$address]. \n";
	sleep(3);
	$now = date("Y-m-d H:i:s");
	echo "[$now] write the message [$msg_body] into DB after 3 trail of push for [$address]. \n";
	return 0;
}

?>

