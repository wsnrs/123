<?php
require_once('xfm_config.php');

$url = 'http://'.$_SERVER['HTTP_HOST'].'/'.'baidu_sitemap_xml.php';
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//var_dump($url);
$url = str_replace('create_sitemap.php', 'baidu_sitemap_xml.php', $url);

if (cfg_is_https()) {
    $url = str_replace('http://', 'https://', $url);
}

$url = 'https://www.chaogmp.com/baidu_sitemap_xml.php';

$sitemap_data = curl_request_xx($url);
echo $sitemap_data;
exit;
file_put_contents('sitemap.xml', $sitemap_data);


if (file_exists('sitemap.xml')) {
    echo 'sitemap 生成成功';
    echo '，<span style="color:blue;">点击查看-><a href="sitemap.xml">网站地图</a></span>';
}
else {
    echo 'sitemap 生成失败';
}



//curl获取百度内容
function curl_request_xx($url, $data=null, $method='get', $https=true){
    $ch = curl_init();//初始化
    curl_setopt($ch, CURLOPT_URL, $url);//访问的URL
    curl_setopt($ch, CURLOPT_HEADER, false);//设置不需要头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//只获取页面内容，但不输出
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    if($https){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//https请求 不验证证书 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//https请求 不验证HOST
    }
    curl_setopt($ch,CURLOPT_ENCODING,'gzip');//百度返回的内容进行了gzip压缩,需要用这个设置解析
    //curl模拟头部信息
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'Accept-Encoding: gzip, deflate, compress',
            'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,zh-TW;q=0.7,ja;q=0.6',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36'
        ));
    if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);//请求方式为post请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//请求数据
    }
    $result = curl_exec($ch);//执行请求
    curl_close($ch);//关闭curl，释放资源
    $result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII,gb2312');//百度默认编码是gb2312 这个设置转化为utf8编码
    return $result;
}


?>