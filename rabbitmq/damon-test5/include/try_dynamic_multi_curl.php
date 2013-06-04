<?php
/* include/push_func.php
 * liuzhengyi
 * how to dynamic add curl handle into multi curl handle?
 * 2013-05-21
 */

function push_curl_xmpost($args, $curl_timeout=1, $results, $retry=4) {
	$curl_queue = curl_multi_init();
        var_dump((string)$curl_queue);
	$map = array();
    $try_times = array();

	foreach ($args as $arg) {
        if(true === $results[$arg['sn']]) {
            break;
        }
		$ch = curl_init();
        var_dump((string)$ch);

		curl_setopt($ch, CURLOPT_URL, $arg['url']);
        /*
        if(intval($curl_timeout) > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, intval($curl_timeout));
        }
        */
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arg['post_data']));

		curl_multi_add_handle($curl_queue, $ch);
		$map[(string) $ch] = $arg['sn'];
		$try_times[(string) $ch] = 0;
        $results[$map[(string) $ch]] = false;
        //echo '$results['.$map[(string) $ch] .'] = false'."\n";   // todo del
	}

	//$responses = array();
	do {
		while (($code = curl_multi_exec($curl_queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;

		if ($code != CURLM_OK) { break; }   // todo: error occured !

		// a request was just completed -- find out which one
		while ($done = curl_multi_info_read($curl_queue)) {
			// get the info and content returned on the request
			$info = curl_getinfo($done['handle']);
			$error = curl_error($done['handle']);
			//$results = callback(curl_multi_getcontent($done['handle']), $delay);
            $return_value = curl_multi_getcontent($done['handle']);
            var_dump($return_value);  // todo : verify the ack
			//$responses[$map[(string) $done['handle']]] = compact('info', 'error', 'results');
            $try_times[(string) $done['handle']] += 1;

			// remove the curl handle that just completed
			if(curl_multi_remove_handle($curl_queue, $done['handle'])) {
                continue;
            }
            echo '$done["handle"]'.(string)$done['handle'].'removed'.'|$return=';var_dump($return_value);

            if(isset($return_value) && 'ok' == $return_value ) {
            // 成功了
                curl_close($done['handle']);
                $results[$map[(string) $done['handle']]] = true;
                //echo '$results['.$map[(string) $done['handle']] .'] = true'."\n";   // todo del
            } else if($retry < $try_times[(string)$done['handle']]) {
            // 重试够了，放弃
                curl_close($done['handle']);
                echo "fail to deliver a msg. retry=$retry, try_times={$try_times[(string) $done['handle']]}\n";
            } else {
            echo "---retry----\n";
            // 重试
                // 此处不可简单将$done['handle']再次放入批处理
                // 因为这个句柄被认为已经处理完毕了
                // 应该需要重新创建一个句柄，并放入批处理队列
                $arg = $args[$map[(string)$done['handle']]];
                $ch = curl_init();
                echo "a new curl resource ---";
                var_dump((string)$ch);
                curl_setopt($ch, CURLOPT_URL, $arg['url']);
                //if(intval($curl_timeout) > 0) {
                //    curl_setopt($ch, CURLOPT_TIMEOUT, intval($curl_timeout));
                //}
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_NOSIGNAL, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arg['post_data']));

                curl_multi_add_handle($curl_queue, $ch);
                $map[(string) $ch] = $arg['sn'];
                $try_times[(string) $ch] = $try_times[(string)$done['handle']];
                echo "new ch's try time = {$try_times[(string)$ch]} \n";
                $results[$map[(string) $ch]] = false;
                //echo '$results['.$map[(string) $ch] .'] = false'."\n";   // todo del
                curl_close($done['handle']);
                echo "retry a msg. retry=$retry, try_times={$try_times[(string) $done['handle']]}\n";
            }
		}

		// Block for data in / output; error handling is done by curl_multi_exec
		if ($active > 0) {
			curl_multi_select($curl_queue, 0.5);
		}

	} while ($active);
    echo 'exit from do-whiel cycle.'."\n";

	curl_multi_close($curl_queue);
	//return $responses;
    return $results;
}

?>
