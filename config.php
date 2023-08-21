<?php
require_once('xfm_config.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$v= isset($_GET['v'])?$_GET['v']:'1';
$key = isset($_GET['key'])?$_GET['key']:'';



/////////////////////////////////////////////////////////////////////////////////////
// 登录验证
// 安全密码文件是否存在
$secret_file = get_secret_code_file();
if (! $secret_file) {
	// 保存安全码
	if (isset($_GET['opt']) && $_GET['opt']==='secret') {
		$path = get_secret_code_dir();
		file_put_contents($path.md5(time()).'.secret', $_POST['password_secret']);
		header('location: config.php?secret='.$_POST['password_secret']);
	}
	else {
		// 显示安全码输入框
		$data = array(
			'password.secret'=>array('input'=>easy_get_config('password_secret'), 'label'=>'安全口令'),
			'submit1'=>array('submit'=>'提交')
			);
		show_secret_code_input('config.php?opt=secret', $data);
		exit;
	}
}


$local_secret_code = file_get_contents($secret_file);

// 验证登录
if (isset($_GET['opt']) && $_GET['opt'] === 'login') {
	if ($_POST['password_secret'] === $local_secret_code) {
		echo '登录成功';
		header('location: config.php?secret='.$local_secret_code);
		exit;
	}
	else {
		//echo '登录失败';
	}
	//exit;
}

if (isset($_GET['secret']) && $_GET['secret'] === $local_secret_code) {
}
else {
	// 显示登录页面
	$data = array(
		'password.secret'=>array('input'=>easy_get_config('password_secret'), 'label'=>'安全口令'),
		'submit1'=>array('submit'=>'提交')
		);
	show_secret_login('config.php?opt=login', $data);
	exit;
}
/////////////////////////////////////////////////////////////////////////////////////






$xfm_config_file = array(
	"sitename_txt",
	"home_title_txt",
	"home_keyword_txt",
	"home_description_txt",
	"spider_txt",
	"menu_txt",
	"tags_txt",
	"baidu_token_txt",
	'wei_yuan_chuang_txt'
);

/*
$sitename_txt = $_POST[];
easy_save_config("sitename_txt");
$home_title_txt = $_POST["home_title_txt"];
easy_save_config("home_title_txt");
$home_keyword_txt = $_POST["home_keyword_txt"];
easy_save_config("home_keyword_txt");
$home_description_txt = $_POST["home_description_txt"];
easy_save_config("home_description_txt");
$spider_txt = $_POST["spider_txt"];
easy_save_config("spider_txt");
$menu_txt = $_POST["menu_txt"];
easy_save_config("menu_txt");
$tags_txt = $_POST["tags_txt"];
easy_save_config("tags_txt");
*/


if (isset($_POST) && count($_POST)>count($xfm_config_file)) {
	//var_dump($_POST);
	if ($_POST['wei_yuan_chuang_txt'] === '') {
		$_POST['wei_yuan_chuang_txt'] = '0';
		//var_dump($_POST);
	}
	else{
		//var_dump($_POST['wei_yuan_chuang_txt']);
	}
	//var_dump($_POST);
	if (save_post_data()) {
		echo '数据已保存，<a href="xfm_reset_all.php">下一步</a>';
	}
	//var_dump($_POST);
	exit;
}



$data = array(
	'sitename.txt'=>array('input'=>easy_get_config('sitename_txt'), 'label'=>'网站名称'),
	'home_title.txt'=>array('textarea'=>easy_get_config('home_title_txt'), 'label'=>'首页TITLE_(T)'),
	'home_keyword.txt'=>array('textarea'=>easy_get_config('home_keyword_txt'), 'label'=>'首页keywords_(K)'),
	'home_description.txt'=>array('textarea'=>easy_get_config('home_description_txt'), 'label'=>'首页description_(D)'),
	'spider.txt'=>array('textarea'=>easy_get_config('spider_txt'), 'label'=>'长尾词列表(建议1000个以上，5000个以下)'),
	'menu.txt'=>array('textarea'=>easy_get_config('menu_txt'), 'label'=>'菜单列表(建议20个以上，30个以下)'),
	'tags.txt'=>array('textarea'=>easy_get_config('tags_txt'), 'label'=>'TAG列表(建议40个以上，60个以下)'),
	'baidu_token.txt'=>array('input'=>easy_get_config('baidu_token_txt'), 'label'=>'百度API提交的TOKEN，例如：8hKe03hrBlLPIlAA，都是16位字符串'),
	'wei_yuan_chuang.txt'=>array('input'=>easy_get_config('wei_yuan_chuang_txt'), 'label'=>'伪原创API，填0表示不使用'),
	'submit1'=>array('submit'=>'提交')
	);

$url = 'config.php?secret='.$local_secret_code;
show_form_html($url, $data);


// 保存数据
function save_post_data() {
	global $xfm_config_file;

	foreach ($xfm_config_file as $value) {
		if (! easy_save_config($value)) {
			return false;
		}
	}

	return true;
	/*
	$sitename_txt = $_POST["sitename_txt"];
	easy_save_config("sitename_txt");
	$home_title_txt = $_POST["home_title_txt"];
	easy_save_config("home_title_txt");
	$home_keyword_txt = $_POST["home_keyword_txt"];
	easy_save_config("home_keyword_txt");
	$home_description_txt = $_POST["home_description_txt"];
	easy_save_config("home_description_txt");
	$spider_txt = $_POST["spider_txt"];
	easy_save_config("spider_txt");
	$menu_txt = $_POST["menu_txt"];
	easy_save_config("menu_txt");
	$tags_txt = $_POST["tags_txt"];
	easy_save_config("tags_txt");
	return true;*/

}


// 显示表格后退出
function show_form_html($url, $data) {
	echo '<!doctype html><html lang="en">
	<head>
	<meta charset="utf-8">
	<title>人工智能自动建站</title>
	<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
	<style>
	textarea{width:300px;}
	input{width:300px;}
	form{color:blue;}
	</style>
	</head>
	<body>';
	echo '<h2>人工智能自动建站</h2>';
	echo '<p style="color:red">初次运行，请先配置.</p>';
	show_form($url, $data);

	echo '</body></html>';
}


function show_form($url, $data) {
	echo '<form action="'.$url.'" method="POST" id="aaabbbc">';
	foreach ($data as $key => $value) {
		if (array_key_exists('input', $value)) {
			//echo $value['input'];
			echo create_input_tag($key, $value['input'], $value['label']);
			echo '<hr>';
		}
		else if (array_key_exists('textarea', $value)) {
			echo create_textarea_tag($key, $value['textarea'], $value['label']);
			echo '<hr>';
		}
		else if (array_key_exists('submit', $value)) {
			echo create_submit_tag($key, $value['submit']);
			echo '<hr>';
		}
		else {
			var_dump($value);
		}
	}
	echo '</form>';
}


function create_textarea_tag($name, $value, $label) {
	return $name.' '.$label. ': <br /><textarea name="'.$name.'" rows="15" id="'.$name.'">'.$value.'</textarea>';
}

function create_input_tag($name, $value, $label) {
	return $name.' '. $label.': <br /><input type="input" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
}

function create_submit_tag($name, $value) {
	return $name. ': <br /><input type="submit" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
}



function get_secret_code_dir() {
	$file_dir = $GLOBALS["config_dir"].DIRECTORY_SEPARATOR;
	return $file_dir;
}

function get_secret_code_file() {
	$tmp_arr = [];
	$file_dir = get_secret_code_dir();
	if (file_exists($file_dir)) {
		$dh  = opendir($file_dir);
		while (false !== ($filename = readdir($dh))) {
			if($filename != ".." && $filename != ".") {
				if (strpos($filename, '.secret')) {
					$tmp_arr[] = $file_dir.$filename;
					//var_dump($tmp_arr);
				}
			}
		}
		closedir($dh);
		return empty($tmp_arr)?false:$tmp_arr[0];
	}
	else {
		return '密码文件错误';
	}

	return empty($tmp_arr)?false:$tmp_arr[0];
}



// 显示登录页面
function show_secret_login($url, $data) {
	echo '<!doctype html><html lang="en">
	<head>
	<meta charset="utf-8">
	<title>人工智能自动建站</title>
	<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
	<style>
	textarea{width:300px;}
	input{width:300px;}
	</style>
	</head>
	<body>';
	echo '<h2>口令验证：</h2>';
	//echo '<form><input name="secret_code" type="text" /></form>';
	show_form($url, $data);

	echo '</body></html>';
}

// 显示表格后退出
function show_secret_code_input($url, $data) {
	echo '<!doctype html><html lang="en">
	<head>
	<meta charset="utf-8">
	<title>人工智能自动建站</title>
	<meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
	<style>
	textarea{width:300px;}
	input{width:300px;}
	</style>
	</head>
	<body>';
	echo '<h2>第一次运行要填安全口令<span style="color:red;">（口令必须记录好）</span>：</h2>';
	//echo '<form><input name="secret_code" type="text" /></form>';
	show_form($url, $data);

	echo '</body></html>';
}
?>