<?php
set_time_limit(0);

require_once('proxy_curl_get.php');


for($i=0; $i<1; $i++) {
    $res = nice_baidu_get('伪原创工具'.$i, $i);
    echo $res;
    ##var_dump(get_baidu_success($res));
// nice_baidu_get($s, $pn);
}


/*
$proxy_list = array(
    "8.210.25.200",
    "47.243.82.34",
    "47.243.85.219",
    "47.243.86.132",
    "8.210.148.207",
    "47.242.17.157",
    "47.243.86.197",
    "47.242.245.89",
    "8.210.98.141",
    "8.210.192.181",
    "47.242.35.69",
    "47.242.75.169",
    "8.210.164.43",
    "8.210.154.88",
    "8.210.76.183",
    "47.242.76.76",
    "47.242.45.161",
    "47.244.162.221"
);

$s = '伪原创工具';
$pn = rand(1, 5);

$key_word = urlencode($s);//需要对关键词进行url解析,否者部分带字符的标题会返回空
//$baidu_url = 'https://220.181.38.149/s?ie=UTF-8&pn='.$pn.'&wd='.$key_word;
//$baidu_url = 'https://www.baidu.com/s?ie=UTF-8&pn='.$pn.'&wd='.$key_word;
//echo $baidu_url;
//$local_res = curl_request_xx($baidu_url);
#$local_res = '';
// 本机请求，如果成功则返回
//if (   !  get_baidu_success($local_res)) {
    #echo '<!-- local -->';
    #echo $local_res;
    //return $local_res;
//}
//else {
    // 重试次数
    for($i=0; $i<5; $i++) {
        shuffle($proxy_list);
        $proxy_baidu_url = 'http://'.$proxy_list[0].'/proxy_a.php?ie=UTF-8&pn='.$pn.'&wd='.$key_word;
        echo $proxy_baidu_url;
        echo '<hr>';
        $proxy_res = curl_request_normal_xx($proxy_baidu_url);
        if (get_baidu_success($proxy_res)) {
            return $proxy_res;
        }
        else {
            file_put_contents('data3883'.DIRECTORY_SEPARATOR.'proxy_failed.txt',
            date('Y-m-d H:i:s').' - '.$proxy_list[0].' - '.$key_word.PHP_EOL,
            FILE_APPEND | LOCK_EX);
        }
    }
//}
*/



?>