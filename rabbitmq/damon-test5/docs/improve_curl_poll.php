<?php
/*
PHP curl 并发最佳实践代码分享

发布：mdxy-dxy 字体：[增加 减小] 类型：转载
在实际项目或者自己编写小工具(比如新闻聚合,商品价格监控,比价)的过程中, 通常需要从第3方网站或者API接口获取数据, 在需要处理1个URL队列时, 为了提高性能, 可以采用cURL提供的curl_multi_*族函数实现简单的并发

本文将探讨两种具体的实现方法, 并对不同的方法做简单的性能对比.

1. 经典cURL并发机制及其存在的问题
经典的cURL实现机制在网上很容易找到, 比如参考PHP在线手册的如下实现方式:
代码如下:
*/

function classic_curl($urls, $delay) {
    $queue = curl_multi_init();
    $map = array();

    foreach ($urls as $url) {
        // create cURL resources
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);

        // add handle
        curl_multi_add_handle($queue, $ch);
        $map[$url] = $ch;
    }

    $active = null;

    // execute the handles
    do {
        $mrc = curl_multi_exec($queue, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active > 0 && $mrc == CURLM_OK) {
        if (curl_multi_select($queue, 0.5) != -1) {
            do {
                $mrc = curl_multi_exec($queue, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    $responses = array();
    foreach ($map as $url=>$ch) {
        $responses[$url] = callback(curl_multi_getcontent($ch), $delay);
        curl_multi_remove_handle($queue, $ch);
        curl_close($ch);
    }

    curl_multi_close($queue);
    return $responses;
}

/*
首先将所有的URL压入并发队列, 然后执行并发过程, 等待所有请求接收完之后进行数据的解析等后续处理. 在实际的处理过程中, 受网络传输的影响, 部分URL的内容会优先于其他URL返回, 但是经典cURL并发必须等待最慢的那个URL返回之后才开始处理, 等待也就意味着CPU的空闲和浪费. 如果URL队列很短, 这种空闲和浪费还处在可接受的范围, 但如果队列很长, 这种等待和浪费将变得不可接受.

2. 改进的Rolling cURL并发方式

仔细分析不难发现经典cURL并发还存在优化的空间, 优化的方式是当某个URL请求完毕之后尽可能快的去处理它, 边处理边等待其他的URL返回, 而不是等待那个最慢的接口返回之后才开始处理等工作, 从而避免CPU的空闲和浪费. 闲话不多说, 下面贴上具体的实现:
代码如下:
*/

function rolling_curl($urls, $delay) {
	$queue = curl_multi_init();
	$map = array();

	foreach ($urls as $url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);

		curl_multi_add_handle($queue, $ch);
		$map[(string) $ch] = $url;
	}

	$responses = array();
	do {
		while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;

		if ($code != CURLM_OK) { break; }

		// a request was just completed -- find out which one
		while ($done = curl_multi_info_read($queue)) {
			// get the info and content returned on the request
			$info = curl_getinfo($done['handle']);
			$error = curl_error($done['handle']);
			$results = callback(curl_multi_getcontent($done['handle']), $delay);
			$responses[$map[(string) $done['handle']]] = compact('info', 'error', 'results');

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
	return $responses;
}


/*
3. 两种并发实现的性能对比

改进前后的性能对比试验在LINUX主机上进行, 测试时使用的并发队列如下:

http://item.taobao.com/item.htm?id=14392877692
http://item.taobao.com/item.htm?id=16231676302
http://item.taobao.com/item.htm?id=17037160462
http://item.taobao.com/item.htm?id=5522416710
http://item.taobao.com/item.htm?id=16551116403
http://item.taobao.com/item.htm?id=14088310973

简要说明下实验设计的原则和性能测试结果的格式: 为保证结果的可靠, 每组实验重复20次, 在单次实验中, 给定相同的接口URL集合, 分别测量Classic(指经典的并发机制)和Rolling(指改进后的并发机制)两种并发机制的耗时(秒为单位), 耗时短者胜出(Winner), 并计算节省的时间(Excellence, 秒为单位)以及性能提升比例(Excel. %). 为了尽量贴近真实的请求而又保持实验的简单, 在对返回结果的处理上只是做了简单的正则表达式匹配, 而没有进行其他复杂的操作. 另外, 为了确定结果处理回调对性能对比测试结果的影响, 可以使用usleep模拟现实中比较负责的数据处理逻辑(如提取, 分词, 写入文件或数据库等).

性能测试中用到的回调函数为:
代码如下:
*/

function callback($data, $delay) {
preg_match_all('/<h3>(.+)<\/h3>/iU', $data, $matches);
usleep($delay);
return compact('data', 'matches');
}

/*
数据处理回调无延迟时: Rolling Curl略优, 但性能提升效果不明显.
数据处理回调延迟5毫秒: Rolling Curl完胜, 性能提升40%左右.
通过上面的性能对比, 在处理URL队列并发的应用场景中Rolling cURL应该是更加的选择, 并发量非常大(1000+)时, 可以控制并发队列的最大长度, 比如20, 每当1个URL返回并处理完毕之后立即加入1个尚未请求的URL到队列中, 这样写出来的代码会更加健壮, 不至于并发数太大而卡死或崩溃. 详细的实现请参考: http://code.google.com/p/rolling-curl/
*/
?>
