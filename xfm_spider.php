<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');

// 加密
if (isset($_GET['pwd'])) {
	if ($_GET['pwd'] !== 'd8d88dw9w9w9') {
		//header('location: /');
	}
}
else {
	//header('location: /');
}


require_once 'func'.DIRECTORY_SEPARATOR.'spider_info.php';         // 判断是否蜘蛛
require_once 'mmlog.php';
require_once 'black_list.php';
require_once('xfm_simple_cache.v.2.php');

$baidu_tail = 'baidu_spider';
$google_tail = 'google_spider';
$bing_spider = 'bing_spider';
$sogou_spider = 'sogou_spider';

$date = isset($_GET['date'])?$_GET['date']:'';
$api_type = isset($_GET['api'])?$_GET['api']:'';

$today_spider = '';
$detail = 'xfm_spider.php?api='.$api_type.'&detail=1';
if ($api_type === '1') {
	$count = intval(get_spider_count($baidu_tail));
	echo '百度蜘蛛：'.$count;
	if ($count < 100000) {
		$last_100 = get_spider_last_100($baidu_tail);
	}
}
else if ($api_type === '2') {
	//echo ''.get_spider_count($google_tail);
	$count = intval(get_spider_count($google_tail));
	echo '谷歌蜘蛛：'.$count;
	if ($count < 100000) {
		$last_100 = get_spider_last_100($google_tail);
	}
}
else if ($api_type === '3') {
	//echo ''.get_spider_count($bing_spider);
	$count = intval(get_spider_count($bing_spider));
	echo '必应蜘蛛：'.$count;
	if ($count < 100000) {
		$last_100 = get_spider_last_100($bing_spider);
	}
}
else if ($api_type === '4') {
	//echo ''.get_spider_count($sogou_spider);
	$count = intval(get_spider_count($sogou_spider));
	echo '搜狗蜘蛛：'.$count;
	if ($count < 100000) {
		$last_100 = get_spider_last_100($sogou_spider);
	}
}

// 显示TOP 100

if (isset($_GET['detail']) && $_GET['detail'] === '1' ) {
	if (! isset($last_100)) {
		echo '<hr>数据超过10W，或者无记录';
		exit;
	}
	echo '<p>蜘蛛最新爬取页面TOP 100：</p>';
	//var_dump($today_spider_last_100);
	foreach ($last_100 as $key => $value) {
		if (strlen($value)>10) {
			$one_row = explode("\t", $value);
			if ($one_row[18] == 'times') {
				continue;
			}
			echo $one_row[18];
			echo ' - ';
			echo show_url($one_row[9]);
			echo '<br>';
			#var_dump($one_row);
		}
	}
	#var_dump($last_100);
}

exit;

function show_url($url) {
	return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
}

// 读取蜘蛛总数量
function get_spider_count($tail) {
	$spider_count = get_count_total_of_day($tail);
	return $spider_count;
}

// 读取最新100条蜘蛛记录
function get_spider_last_100($tail) {
	$today_spider_filename = csv_get_log_file_name($tail);
	if (file_exists($today_spider_filename)) {
		$today_data = file_get_contents($today_spider_filename);
		$today_spider = explode("\n", $today_data);
	}
	else {
		return array();
	}

	// 只显示最新的100个，不然怕太长了
	$spider_count = count($today_spider);
	if ($spider_count > 100) {
		$last_100 = array_slice($today_spider, $spider_count-100);
	}
	else {
		$last_100 = $today_spider;
	}

	return $last_100;
}

function get_google_spider($google_tail) {
	$ret_str = '';
	$today_spider_filename = csv_get_log_file_name($google_tail);
	if (file_exists($today_spider_filename)) {
		$today_data = file_get_contents($today_spider_filename);
		$today_spider = explode("\n", $today_data);
		$spider_count = get_count_total_of_day($google_tail);
	}
	else {
		$ret_str = '暂无数据';
	}

	if (isset($_GET['api'])) {
		$ret_str = $_SERVER['HTTP_HOST']; // 当前域名
		$ret_str .= ' 蜘蛛数：';
		$ret_str .= $spider_count;
	}

	return $ret_str;
}


// 必应蜘蛛数
function get_bing_spider($bing_spider) {
	$ret_str = '';
	$today_spider_filename = csv_get_log_file_name($bing_spider);
	if (file_exists($today_spider_filename)) {
		$today_data = file_get_contents($today_spider_filename);
		$today_spider = explode("\n", $today_data);
		$spider_count = get_count_total_of_day($bing_spider);
	}
	else {
		$ret_str = '暂无数据';
	}

	if (isset($_GET['api'])) {
		$ret_str = $_SERVER['HTTP_HOST']; // 当前域名
		$ret_str .= ' 蜘蛛数：';
		$ret_str .= $spider_count;
	}

	return $ret_str;
}

// 百度蜘蛛数
function get_baidu_spider($baidu_tail) {
	global $today_spider;
	$ret_str = '';
	$today_spider_filename = csv_get_log_file_name($baidu_tail);
	//var_dump($today_spider_filename);
	if (file_exists($today_spider_filename)) {
		$today_data = file_get_contents($today_spider_filename);
		$today_spider = explode("\n", $today_data);
		//var_dump($today_spider);
		$spider_count = get_count_total_of_day($baidu_tail);
	}
	else {
		$ret_str = '暂无数据';
	}

	if (isset($_GET['api'])) {
		$ret_str = $_SERVER['HTTP_HOST']; // 当前域名
		$ret_str .= ' 蜘蛛数：';
		$ret_str .= $spider_count;
	}

	return $ret_str;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		strong {color:blue;}
	</style>
</head>
<body>

<?php

#var_dump($today_spider);
echo '日期：<strong>' . date('Y-m-d H:i:s');
echo '</strong><br>';
echo '蜘蛛数：<strong>';

echo $spider_count;
echo '</strong><hr>';




?>

</body>
</html>