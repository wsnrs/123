<?php
require_once('xfm_config.php');

if (0) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
else {
    ini_set("display_errors", "Off");
    ini_set('display_startup_errors',1);
    error_reporting(-1);
    ini_set('error_log', dirname(__FILE__) . DIRECTORY_SEPARATOR . $error_log_filename);
}

date_default_timezone_set('PRC');


define("FOLDER_LOG", $shuju_dir);

$PHP_SELF = isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:"";
$PHP_QUERYSTRING = $_SERVER['QUERY_STRING'];
$PHP_DOMAIN = $_SERVER['SERVER_NAME'];
$PHP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$PHP_SCHEME = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
$PHP_PORT = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
$PHP_SITEURL = $PHP_SCHEME.$PHP_DOMAIN.$PHP_PORT;
$PHP_URL = $PHP_SCHEME.$PHP_DOMAIN.$PHP_PORT.$PHP_SELF.($PHP_QUERYSTRING ? '?'.$PHP_QUERYSTRING : '');


$log_server_infos = array(
"id" => isset($_GET['v'])?$_GET['v']:"",
"j" => isset($_GET['w'])?$_GET['w']:"",
"b" => isset($_GET['l'])?$_GET['l']:"",
"HTTP_ACCEPT" => isset($_SERVER["HTTP_ACCEPT"])?$_SERVER["HTTP_ACCEPT"]:"no value", // [HTTP_ACCEPT],
"HTTP_ACCEPT_ENCODING" => isset($_SERVER["HTTP_ACCEPT_ENCODING"])?$_SERVER["HTTP_ACCEPT_ENCODING"]:"no value",
"ip" => isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"no value", // [REMOTE_ADDR]
"ip2" => isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:"no value", // [HTTP_X_FORWARDED_FOR]
"ip3" => isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:"no value", // [HTTP_CLIENT_IP]
"ua" => isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:"no value", // [HTTP_USER_AGENT]
"qs" => $PHP_URL,
"referer" => isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"no value", //[HTTP_REFERER]
"out" => ""
);

$log_client_infos = array(
"platform" => isset($_GET['p1'])?$_GET['p1']:"",
"height" => isset($_GET['p2'])?$_GET['p2']:"",
"width" => isset($_GET['p3'])?$_GET['p3']:"",
"color" => isset($_GET['p4'])?$_GET['p4']:"",
"jsreferer" => isset($_GET['p5'])?$_GET['p5']:"",
"remark" => "",
);


//log6('wx');

//echo "<pre>";
//print_r($log_server_infos);
//print_r($log_client_infos);
//echo "</pre>";

function csv_get_log_file_name($tail) {
   $cur_sec = intval(date("i", time()));
   $cur_file = sprintf("%s%s%s%s%s_%d_%s", dirname($_SERVER["SCRIPT_FILENAME"]), DIRECTORY_SEPARATOR.FOLDER_LOG.DIRECTORY_SEPARATOR, date("Ymd", time()), DIRECTORY_SEPARATOR, '', 6, $tail.".csv");
   if (!is_dir(dirname($cur_file))) {
      @mkdir(dirname($cur_file),0755,true);
   }
   return $cur_file;
}

function array_to_str($array_server, $array_client) {
   $record = "";
   foreach ($array_server as $v) {
      $record = $record . $v . "\t";
   }

   foreach ($array_client as $s) {
      $record = $record . $s . "\t";
   }

   $record = $record . date("Y-m-d H:i:s", time()) . "\n";

   return $record;
}

function array_key_to_str($array_server, $array_client) {
   $record = "";
   foreach ($array_server as $k=>$v) {
      $record = $record . $k . "\t";
   }

   foreach ($array_client as $k=>$s) {
      $record = $record . $k . "\t";
   }

   $record = $record . "times" . "\n";

   return $record;
}

function csv_log($filename, $array_server, $array_client) {

   $record = array_to_str($array_server, $array_client);

   if (file_exists($filename)) {
      if (is_writable($filename)) {
         @file_put_contents($filename, $record, FILE_APPEND|LOCK_EX);
      } else {
         csv_error("ÎÄ¼þ²»¿ÉÐ´".$filename);
      }
   }
   else {
      $csv_head = array_key_to_str($array_server, $array_client);
      @file_put_contents($filename, $csv_head.$record, FILE_APPEND|LOCK_EX);
   }
}

function log6($tail) {
	global $log_server_infos;
	global $log_client_infos;
	easy_counter_count($tail);
	csv_log(csv_get_log_file_name($tail), $log_server_infos, $log_client_infos);
}

function log7($tail, $remark) {
	global $log_server_infos;
	global $log_client_infos;
	easy_counter_count($tail);
  easy_counter_count_total();
  easy_counter_count_total_of_day($tail);
	$log_client_infos["remark"] = $remark;
	csv_log(csv_get_log_file_name($tail), $log_server_infos, $log_client_infos);
}

//log7(md5('aa'), 'a');
//log7("test2", "good");

function easy_counter($file_pre) {
   $filename = date('Ymd').$file_pre.'counter.txt';
   file_put_contents($filename, "1", FILE_APPEND|LOCK_EX);
}

//log7('aaaaaaaaa', 'b');

function easy_counter_count_total() {
   $filename = dirname($_SERVER["SCRIPT_FILENAME"]).DIRECTORY_SEPARATOR.FOLDER_LOG.DIRECTORY_SEPARATOR.date("Ymd", time()).'.txt';
//   var_dump($filename);
   mkdirs(dirname($filename));
   file_put_contents($filename, "1", FILE_APPEND|LOCK_EX);
}

function easy_counter_count_total_of_day($tail) {
   $filename = dirname($_SERVER["SCRIPT_FILENAME"]).DIRECTORY_SEPARATOR.FOLDER_LOG.DIRECTORY_SEPARATOR.date("Ymd", time()).DIRECTORY_SEPARATOR.$tail.'.txt';
//   var_dump($filename);
   mkdirs(dirname($filename));
   file_put_contents($filename, "1", FILE_APPEND|LOCK_EX);
}

function get_count_total_of_day($tail) {
   $filename = dirname($_SERVER["SCRIPT_FILENAME"]).DIRECTORY_SEPARATOR.FOLDER_LOG.DIRECTORY_SEPARATOR.date("Ymd", time()).DIRECTORY_SEPARATOR.$tail.'.txt';
//   var_dump($filename);
//   mkdirs(dirname($filename));
//   file_put_contents($filename, "1", FILE_APPEND|LOCK_EX);
    $size = 0;
    if (file_exists($filename)) {
      $size = filesize($filename);
    }
    return $size;
}

function easy_counter_count($file_pre) {
   $filename = FOLDER_LOG.DIRECTORY_SEPARATOR.$file_pre.'_count.txt';
//   var_dump($filename);
   mkdirs(dirname($filename));
   file_put_contents($filename, "1", FILE_APPEND|LOCK_EX);
}

function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}


// 获取API访问次数
function get_xfm_api_counter($file_pre) {
    $filename = 'count'.DIRECTORY_SEPARATOR.$file_pre.'.txt';
    $size = 0;
    if (file_exists($filename)) {
      $size = filesize($filename);
    }
    return $size;
}
