<?php
require_once('3rd/jsonp.php');


function baidu_tuijian($keyword) {
    $key_word = urlencode($keyword);//需要对关键词进行url解析,否者部分带字符的标题会返回空
    //$baidu_url = 'https://ipv6.baidu.com/s?ie=UTF-8&pn='.$pn.'&wd='.$key_word;
    $baidu_url = 'https://www.baidu.com/sugrec?pre=1&p=3&ie=utf-8&json=1&prod=pc&from=pc_web&sugsid=33802,33822,31254,33848,33756,33855,26350,33811&wd='.$key_word.'&req=2&csor=3&pwd=xfm&cb=jQuery1102016255492725315057_1618799783757&_=1618799783761';
//echo '<!--';
//echo $baidu_url;
//echo '-->';
    $local_res = curl_request_rand_ua($baidu_url);
    $arr = jsonp_decode($local_res, true);
    $tuijian_arr = [];
    if (isset($arr["g"]) && count($arr["g"])>0) {
        foreach($arr["g"] as $value) {
            //var_dump($value);
            $tuijian_arr[] = $value["q"];
        }

        return $tuijian_arr;
    }
    else {
        return false;
    }
}

function my_rand_user_agent() {
    $val = rand(1000, 9999);
    $sstr = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.'.strval($val).'.77 Safari/537.36';
    return $sstr;
}


//curl获取百度内容
function curl_request_rand_ua($url, $data=null, $method='get', $https=true) {
    $ch = curl_init();//初始化
    curl_setopt($ch, CURLOPT_URL, $url);//访问的URL
    curl_setopt($ch, CURLOPT_HEADER, false);//设置不需要头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//只获取页面内容，但不输出
    if($https){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//https请求 不验证证书 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//https请求 不验证HOST
    }

    curl_setopt($ch,CURLOPT_ENCODING,'gzip');//百度返回的内容进行了gzip压缩,需要用这个设置解析
    //curl模拟头部信息
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,zh-TW;q=0.7,ja;q=0.6',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Host: www.baidu.com',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'is_referer: https://www.baidu.com/',
            'is_xhr: 1',
            'Referer: https://www.baidu.com/',
            'User-Agent: '.my_rand_user_agent()
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