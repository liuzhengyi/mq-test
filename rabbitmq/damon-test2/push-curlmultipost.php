<?php
/* push-curlmultipost.php
 * liuzhengyi 
 * 2013-05-15
 */
//push($argv[1], $argv[2]);

function push($msg_body, $address) {
	// todo push the message to client, if cannot, write it to db and record this incident in cache
	$now = date("Y-m-d H:i:s");
	echo "[$now] get the msg [$msg_body]. \n";
	$now = date("Y-m-d H:i:s");
	echo "[$now] trying to push msg [$msg_body] to [$address]. \n";
//	sleep(3);
	$url = 'http://dl.gipsa.name/receive_push.php';
	$data = array(
		'msg' => $msg_body,
		'time' => time(),
	);
	//$post_string = http_build_query($data, '', '&amp;');
	//http_post_data($url, $post_string);
	curl_post($url, $data);
	// todo wait for client's ack
	$now = date("Y-m-d H:i:s");
	echo "[$now] write the message [$msg_body] into DB after 3 trail of push for [$address]. \n";
	return 0;
}

function curl_post($url, $post_data) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data, '', '&amp;'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
	$data = curl_exec($ch);
	curl_close($ch);
	if('ok' == $data) {
		return true;
	}
	return false;
}

function curl_multi_post($args){
	if(!is_array($args) ) {
		exit('curl_multi_post need parameter 2 to be array.');
	}
	$mh = curl_multi_init();
	$chs = array();	// 记录存放 $ch 供释放资源时使用
	foreach($args as $arg) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $arg['url']);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arg['post_data']));
		curl_multi_add_handle($mh, $ch);
		$chs[] = $ch;

	}
	$active = null;
	do {
		$mrc = curl_multi_exec($mh, $active);
	} while( $mrc == CURLM_CALL_MULTI_PERFORM );
	
	while($active && $mrc == CURLM_OK) {
		if(-1 != curl_multi_select($mh)) {
			do {
				$mrc = curl_multi_exec($mh, $active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		}
	}
	foreach($chs as $ch) {
		curl_multi_remove_handle($mh, $ch);
	}
	curl_multi_close($mh);
}

?>
