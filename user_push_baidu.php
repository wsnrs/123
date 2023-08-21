<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
#require_once('baidu_push_api.php');
require_once("xfm_config.php");
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
require_once("xfm_tag_parser.php");
require_once('xfm_simple_cache.v.2.php');
//define('BASE_DOMAIN', 'http://'.$_SERVER['SERVER_NAME'].'/');
// 主动执行百度推送
//$is_rewrite = true;

$push_time = isset($_GET['total'])?intval($_GET['total']):5;

for ($i = 0; $i < $push_time; $i++) {
    $new_keyword = get_one_keyword();
    //var_dump($new_keyword);
    #echo '/*'.PHP_EOL;

    // 写满18个首页链接，就不继续写了。
    if (count($news_list) > $index_max_list) {
        file_put_contents(INDEX_LIST_LOCK, '1');
    }
    //var_dump($_SERVER);

    push_one_keyword($new_keyword);
}
#echo PHP_EOL.'*/';




function push_one_keyword($keyword) {
    global $token;
    xfm_put_keyword_cache($keyword);
    // 获取关键词短网址
    $short_url = short_md5($keyword);
    $domain_url = get_domain_url($short_url);
    //echo '1:<br>';
    //var_dump($domain_url);
    #$tmp_self_url = get_domain_url('');
    #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
    #echo '<br>2:<br>';
    #var_dump('location: '. $tmp_self_url);
    //http://www.scfzdfy.com/baidu_push_api.php?url=http://www.scfzdfy.com/868o7ju2.html
    //header('location: baidu_push_api.php?url='.urlencode($domain_url));
    $url = $domain_url;

    if (cfg_is_https()) {
        $url = str_replace('http://', 'https://', $url);
    }

    echo '/* fvck?'.PHP_EOL;
    if (xfm_get_keyword_exists($url)) {
        echo $url;
        echo ' 已推送';
    }
    else {
        echo '推送URL：'.$url.PHP_EOL;
        //echo 'site：'.$site.PHP_EOL;
        $site = $_SERVER['SERVER_NAME'];
        $site = $_SERVER['HTTP_HOST'];
        $push_ret = my_baidu_push($url, $site, $token);
        if (strpos($push_ret, 'success') !== false) {
            xfm_put_keyword_cache($url);
            echo ' cached. ';
            echo $push_ret;
        }
        else {
            echo $push_ret;
        }
    }
    echo PHP_EOL.'*/';


    #return $tmp_self_url;
}

function my_baidu_push($url, $site, $token) {
    $urls = array(
        $url,
    );
    $api = 'http://data.zz.baidu.com/urls?site='.$site.'&token='.$token;
    echo '/*' . PHP_EOL;
    echo $api;
    ECHO PHP_EOL.'*/';
    $ch = curl_init();
    $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    return $result;
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