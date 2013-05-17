<?php
/* include/push_func.php
 * liuzhengyi 
 * 2013-05-16
 */
function push_writelog($msg_body, $address, $logfile) {
	// todo push the message to client, if cannot, write it to db and record this incident in cache
    if(!($fd = fopen($logfile, 'w+'))) {
        $err_msg = 'file:[ ' . __FILE__ . ']; function: [' . __FUNCTION__ . ']; error [openfile '.$logfile.' failed. ]'."\n";
        exit($err_msg);
    }
	$now = date("Y-m-d H:i:s");
	$log_msg1 =  "[$now] get the msg [$msg_body]. \n";
    fwrite($fd, $log_msg1);
	$now = date("Y-m-d H:i:s");
	$log_msg2 =  "[$now] trying to push msg [$msg_body] to [$address]. \n";
    fwrite($fd, $log_msg2);
    //	sleep(3);
	//$url = 'http://dl.gipsa.name/receive_push.php';
	//$data = array( 'msg' => $msg_body, 'time' => time(),);
	//$post_string = http_build_query($data, '', '&amp;');
	//http_post_data($url, $post_string);
	// todo wait for client's ack
	$now = date("Y-m-d H:i:s");
	$log_msg3 = "[$now] write the message [$msg_body] into DB after 3 trail of push for [$address]. \n";
    fwrite($fd, $log_msg3);
    fclose($fd);
	return 0;
}

function push_curl_post($msg_body, $address) {
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
	// $post_string = http_build_query($data, '', '&amp;');
	// http_post_data($url, $post_string);
	curl_single_post($url, $data);
	// todo wait for client's ack
	$now = date("Y-m-d H:i:s");
	echo "[$now] write the message [$msg_body] into DB after 3 trail of push for [$address]. \n";
	return 0;
}

function curl_single_post($url, $post_data) {
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

/*
 * 使用 php扩展中原生的curl批处理 curl_mulit_exec()
 *
 * $args是一个二维数组，包含内容$arg数组，
 * $arg = array('url'=>'http://xxxx', 'post_msg'=>'xxxx');
 */
function push_curl_mpost($args){
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
    /*
    do {
        usleep(5000);
        curl_multi_exec($mh, $active);
    } while ($active > 0);
    */

	foreach($chs as $ch) {
		curl_multi_remove_handle($mh, $ch);
	}
	curl_multi_close($mh);
}

function push_curl_xmpost($args) {
	$queue = curl_multi_init(); 
	$map = array(); 

	foreach ($args as $arg) { 
		$ch = curl_init(); 

		curl_setopt($ch, CURLOPT_URL, $arg['url']); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 6); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_NOSIGNAL, true); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arg['post_data']));

		curl_multi_add_handle($queue, $ch); 
		$map[(string) $ch] = $arg['url']; 
	} 

	//$responses = array(); 
	do { 
		while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM) ; 

		if ($code != CURLM_OK) { break; } 

		// a request was just completed -- find out which one 
		while ($done = curl_multi_info_read($queue)) {
			// get the info and content returned on the request 
			$info = curl_getinfo($done['handle']); 
			$error = curl_error($done['handle']); 
			//$results = callback(curl_multi_getcontent($done['handle']), $delay); 
            $usleeptime = 2000; $timeout = 10001;
            /*
            while(!$return = curl_multi_getcontent($done['handle']) and $usleeptime < $timeout) {
                usleep($usleeptime+=2000);
            }
            */
            $return = curl_multi_getcontent($done['handle']);
            var_dump($return);  // todo : verify the ack
			//$responses[$map[(string) $done['handle']]] = compact('info', 'error', 'results'); 

			// remove the curl handle that just completed 
			curl_multi_remove_handle($queue, $done['handle']); 
			curl_close($done['handle']); 
		} 

		// Block for data in / output; error handling is done by curl_multi_exec 
		if ($active > 0) { 
			curl_multi_select($queue, 0.5); 
		} 

	} while ($active); 

	curl_multi_close($queue); 
//	return $responses; 
} 

?>
