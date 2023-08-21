<?php
require_once('xfm_config.php');

$url = 'http://'.$_SERVER['HTTP_HOST'].'/'.'baidu_sitemap_xml.php';
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//var_dump($url);
$url = str_replace('create_sitemap.php', 'baidu_sitemap_xml.php', $url);

if (cfg_is_https()) {
    $url = str_replace('http://', 'https://', $url);
}

//$url = 'https://www.chaogmp.com/baidu_sitemap_xml.php';
$sitemap_data = curl_request($url);

//file_put_contents('sitemap.xml', $sitemap_data);
echo '<iframe src="'.$url.'"></iframe>';

if (file_exists('sitemap.xml')) {
    echo 'sitemap 生成成功';
    echo '，<span style="color:blue;">点击查看-><a href="sitemap.xml">网站地图</a></span>';
}
else {
    echo 'sitemap 生成失败';
}



//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url,$post='',$cookie='', $returnCookie=0){
    if (! extension_loaded('curl')) {
        file_exists('./ext/php_curl.dll') && dl('php_curl.dll'); // 加载扩展
    }
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    if (ini_get('open_basedir') == '' && strtolower(ini_get('safe_mode')) != 'on') {
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    }
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
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