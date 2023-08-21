<?php

define('XFM_VERSION', 'v.2.3');

require_once('func'.DIRECTORY_SEPARATOR.'xfm_txt_func.php');
//require_once('show_php_error.php');		// 显示错误
define('IS_DEBUG', false);              // 是否处于调试状态
define('DS', DIRECTORY_SEPARATOR);		// 简写

// 配置信息 ======================================================
define('KW_FILE', 'spkder.txt');                                         // 长尾词文件
define('DATE_FM', 'Y-m-d H:i');                                          // 时间格式
define('DEFAULT_KW', '小学作文');                                         // 默认关键词
define('BASE_DOMAIN', 'http://'.$_SERVER['SERVER_NAME'].'/');            // 域名
define('SITE_NAME', '人工智能自动建站');                                    // 网站名称

define('INDEX_MAX_LIST', 150);                                           // 首页列表个数
// 配置信息结束，其他请勿修改 ====================================
ini_set('date.timezone','Asia/Shanghai');								// 时区
// 是否伪原创，''为不伪原创，填地址则伪原创
$weiyuanchuang = '';

// 自动配图api
$ai_image = '';

// 是否使用干扰码
$ascii_code = false; // 如果使用，改为 true，如果不使用，改为 false

$image_api = '';		// 图片API
$image_rate = 2;										// 每10篇文章出现一个图片
$shuju_dir = 'data3883';									// 缓存目录
$config_dir = 'config';										// 配置文件保存目录
$error_log_filename = 'system_error.txt';					// 错误文件

define('TMP_DIR', 'seo_templates');                      // 模板文件地址
$template_dir = TMP_DIR;									// 模板文件目录

$keyword_list = $config_dir.DIRECTORY_SEPARATOR.'spider.txt';  // 关键词列表

// 随机网站名
if (file_exists($config_dir.DIRECTORY_SEPARATOR.'sitename.rand.txt')) {
	$sitename_arr = xfm_txt_to_array($config_dir.DIRECTORY_SEPARATOR.'sitename.rand.txt');
	$sitename = $sitename_arr[rand(0, count($sitename_arr)-1)];
}
else {
	$sitename = easy_get_config('sitename_txt');    // 网站名称
}



$http_header = 'http://';
if (cfg_is_https()) {
	$http_header = 'https://';
}


define('INDEX_LIST_LOCK', $shuju_dir.DIRECTORY_SEPARATOR.'index_list.lock'); // 首页链接列表写入开关
$index_lock_file = INDEX_LIST_LOCK;             // 首页链接文件锁
$max_index_list = INDEX_MAX_LIST;               // 首页链接数
$is_debug = IS_DEBUG;
$is_rewrite = true;     // 是否伪静态 true 开，false 关
$menu_folder = false;	// 菜单目录

$template_dir = TMP_DIR;
$index_max_list = INDEX_MAX_LIST;
$auto_update_index = true;					// 首页每天更新

/* 随机阅读数 其实次数 */
$read_count_begin = 1000;
$read_count_end = 9999;

/* 文章日期格式 */
/* 0 为 2021-5-1
   1 为 2021-5-1 11:12
   2 为 2021-5-1 11:12:13
*/

$date_format_type = 1; // 这里修改时间格式，顺序从0开始
/*                           0         1              2      */
$data_format_arr = array('Y-m-d', 'Y-m-d H:i', 'Y-m-d H:i:s');
$date_format = $data_format_arr[$date_format_type];

$title_separator_options = array('引发网友热议,','网友忍不住了,',',网友的观点狠耐人寻味','刷屏,',',网友的观点有趣：',',网友的观点引人思考：',',网友的观点有深意：',',网友的观点发人深省：',',网友的观点令人着迷：',',网友的观点有启示性：',',网友的观点有思想性：',',网友的观点引发共鸣：',',网友的观点有内涵：',',网友的观点令人难忘：',',网友的观点引人入胜：',',网友的观点深思熟虑：',',网友的观点想象力激发：',',网友的观点引发好奇心：',',网友的观点有趣的：',',网友的观点有内涵的：',',网友的观点令人难以忘怀：',',网友的观点有启发性的：',',网友的观点有趣味的：',',网友的观点有深度的：',',网友的观点引人思考的：',',网友的观点有迷惑性的：',',网友的观点引发共鸣的：',',网友的观点引人入胜的：',',网友的观点富有内涵的：',',网友的观点引人深思的：',',网友的观点引人好奇的：',',网友的观点令人回味的：',',网友的观点令人惊叹的：',',网友的观点令人着迷的：',',网友的观点意味深长的：',',网友的观点令人动容的：',',网友的观点引发探索的：',',网友的观点令人深思的：',',网友的观点引发好奇的：',',网友的观点引人遐想的：',',网友的观点引人咀嚼的：',',网友的观点引人入迷的：',',网友的观点富有启发的：',',网友的观点引人疑惑的：',',网友的观点引人想象的：',',网友的观点引人挖掘的：',',网友的观点引人感悟的：',',网友的观点引人琢磨的：',',网友的观点引发联想的：',',网友的观点引人追思的：',',网友的观点引人探幽的：',',网友的观点引人遐思的：',',网友的观点引人沉醉的：',',网友的观点引人追忆的：',',网友的观点引人思绪万千的：',',网友的观点引人回味无穷的：',',网友的观点引人陷入思考的：',',网友的观点引人探索的奥秘的：',',网友的观点引人瞩目的：',',网友的观点引人心驰神往的：',',网友的观点引人驻足的：',',网友的观点引人萦绕心头的：',',网友的观点引人沉迷的：',',网友的观点引人心生向往的：',',网友的观点意味深长：',',网友的观点耐人咀嚼：',',网友的观点沉思熟虑：',',网友的观点寻根问底：',',网友的观点钻牛角尖：',',网友的观点让人费解：',',网友的观点饶有兴致：',',网友的观点令人回味：',',网友的观点深入思考：',',网友的观点引人深思：',',网友的观点余音绕梁：',',网友的观点沁人心脾：',',网友的观点扣人心弦：',',网友的观点言近旨远：',',网友的观点言简意赅：',',网友的观点含英咀华：',',网友的观点深入人心：');
$title_separator = $title_separator_options[array_rand($title_separator_options)];

/* URL 伪静态规则
1、http://www.baidu.com/xxxxxxxx.html
2、http://www.baidu.com/xxxxxxxx
*/
$url_rewrite_type = 1;

// 方便本地调试，服务器可以无视这句
if (strpos($_SERVER['SERVER_NAME'], '127.0.0.1') !== false) {
    $is_rewrite = false;
}

/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
// 百度推送
$token = easy_get_config("baidu_token_txt");; // 你的TOKEN
$site = $_SERVER['SERVER_NAME']; // 你的域名
$baidu_push = true;
/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */


$xfm_config_file = array(
	"sitename_txt",
	"home_title_txt",
	"home_keyword_txt",
	"home_description_txt",
	"spider_txt",
	"menu_txt",
	"tags_txt"
);


// 用来读取配置文件
function easy_get_config($param_name) {
	$file_name = 'config'.DIRECTORY_SEPARATOR.str_replace('_', '.', $param_name);
	if (file_exists($file_name)) {
		return file_get_contents($file_name);
	}

	return '';
}


// 用来保存配置文件
function easy_save_config($param_name) {
	$data = isset($_POST[$param_name])?$_POST[$param_name]:'';
	if ($data === '') {
		echo $param_name.' 为空，必须填写';
		return false;
	}
	else {
		$file_name = 'config'.DIRECTORY_SEPARATOR.str_replace('_', '.', $param_name);
		file_put_contents($file_name, $data);
	}

	return true;
}


function get_rand_time_week() {
    global $data_format_arr;
    $rnd_hour = rand(22, 168);
    $rnd_time = date($data_format_arr[0], strtotime("-".$rnd_hour." hour"));
    return $rnd_time;
}


function cfg_is_https() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}

?>