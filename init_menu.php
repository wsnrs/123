<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
#require_once('baidu_push_api.php');
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
define('BASE_DOMAIN', 'http://'.$_SERVER['SERVER_NAME'].'/');
// 主动执行百度推送

$new_keyword = get_one_keyword();

echo '/*'.PHP_EOL;
push_one_keyword($new_keyword);

echo PHP_EOL.'*/';


function push_one_keyword($keyword) {
    xfm_put_keyword_cache($keyword);
    // 获取关键词短网址
    $short_url = short_md5($keyword);
    $domain_url = get_domain_url($short_url);
    echo '1:<br>';
    var_dump($domain_url);
    #$tmp_self_url = get_domain_url('');
    #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
    #echo '<br>2:<br>';
    #var_dump('location: '. $tmp_self_url);
    header('location: '. $domain_url);
    #return $tmp_self_url;
}


function get_one_keyword() {
    global $keyword_list;
    $kws = get_keyword($keyword_list);
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


?>