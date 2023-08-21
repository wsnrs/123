<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
#require_once('baidu_push_api.php');
require_once("xfm_config.php");
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
require_once("xfm_tag_parser.php");
//define('BASE_DOMAIN', 'http://'.$_SERVER['SERVER_NAME'].'/');
// 主动执行百度推送
//$is_rewrite = true;

$new_keyword = get_one_keyword();

#echo '/*'.PHP_EOL;

// 写满18个首页链接，就不继续写了。
if (count($news_list) > $index_max_list) {
    file_put_contents(INDEX_LIST_LOCK, '1');
}


push_one_keyword($new_keyword);

#echo PHP_EOL.'*/';




function push_one_keyword($keyword) {
    xfm_put_keyword_cache($keyword);
    // 获取关键词短网址
    $short_url = short_md5($keyword);
    $domain_url = get_domain_url($short_url);
    if (cfg_is_https()) {
        $domain_url = str_replace('http://', 'https://', $domain_url);
    }
    //echo '1:<br>';
    //var_dump($domain_url);
    #$tmp_self_url = get_domain_url('');
    #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
    #echo '<br>2:<br>';
    #var_dump('location: '. $tmp_self_url);
    //var_dump($domain_url);
    header('location: '. $domain_url);
    #return $tmp_self_url;
}


function get_one_keyword() {
    global $keyword_list;
    //var_dump($keyword_list);
    $kws = get_keyword($keyword_list);
    //var_dump($kws);
    return $kws[0];
}


/*
echo '/*'.PHP_EOL;
if (xfm_get_keyword_exists($url)) {
    echo $url;
    echo ' 已推送';
}
else {
    echo '推送URL：'.$url;
    $push_ret = my_baidu_push($url, $site);
    if (strpos($push_ret, 'success') !== false) {
        xfm_put_keyword_cache($url);
        echo ' cached. ';
        echo $push_ret;
    }
    else {
        echo $push_ret;
    }
}
*/
//echo PHP_EOL.'*/';


/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - https://fetch.spec.whatwg.org/#http-cors-protocol
 *
 */
function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
    //echo "You have CORS!";
}
?>