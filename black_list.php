<?php





function is_black_list() {
	if (is_short_ua()) { // UA信息太短，基本都是机器人
		return true;
	}


	if (is_black_ua()) { // UA黑名单
		return true;
	}

	return false;
}



// 只允许读取缓存的UA
function cache_only_ua() {
	$black_list = array('google.com');
	$php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
	foreach ($black_list as $key => $value) {
		if (strpos($php_ua, $value) !== false) {
			return true;
		}
	}

	return false;
}


#var_dump(is_black_list());
#var_dump(is_black_ua());

function is_black_ua() {
	$black_list = array('go-resty', 'trendiction', 'mail.ru', 'HttpClient','xforce-security', 'WinHttpRequest', 'Apache-HttpClient', 'ahrefs.com', 'MegaIndex', 'opensiteexplorer', 'yandex', 'mj12bot', 'aspiegel.com','babbar.tech','semrush.com','webmeup-crawler');
	$php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
	foreach ($black_list as $key => $value) {
		if (strpos($php_ua, $value) !== false) {
			return true;
		}
	}

	return false;
}

function is_short_ua() {
	$php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
	if (strlen($php_ua) < 40) {
		return true;
	}

	return false;
}




?>
