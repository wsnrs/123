<?php
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
    //echo '1:<br>';
    //var_dump($domain_url);
    #$tmp_self_url = get_domain_url('');
    #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
    #echo '<br>2:<br>';
    #var_dump('location: '. $tmp_self_url);
    spider_curl_request($domain_url);
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


//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function spider_curl_request($url,$post='',$cookie='', $returnCookie=0){
    if (! extension_loaded('curl')) {
        file_exists('./ext/php_curl.dll') && dl('php_curl.dll'); // 加载扩展
    }
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)');
    if (ini_get('open_basedir') == '' && strtolower(ini_get('safe_mode')) != 'on'){ 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    }
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "https://www.baidu.com/?fake");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 150);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
}

?>