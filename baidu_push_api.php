<?php
// 警告：千万不要实时推送，很有可能推送等待导致程序死循环
// 这里使用JS 异步推送
require_once('xfm_simple_cache.v.2.php');
require_once("xfm_config.php"); // 百度 TOKEN 在这里面配置


// 百度推送
//$token = 'X5sDWKGPsyRYNtLL';
//$site = 'www.baidu.com';

$url = isset($_GET['url'])?$_GET['url']:'';
//

echo '/* fuck?'.PHP_EOL;
if (xfm_get_keyword_exists($url)) {
    echo $url;
    echo ' 已推送';
}
else {
    echo '推送URL：'.$url.PHP_EOL;
    //echo 'site：'.$site.PHP_EOL;

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



function my_baidu_push($url, $site, $token) {
    $urls = array(
        $url,
    );
    $api = 'http://data.zz.baidu.com/urls?site='.$site.'&token='.$token;
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


?>