<?php

#echo nice_baidu_get('百度', 1);


function get_baidu_success($res) {
    if (strpos($res, '百度为您找到相关结果') !== false) {
        return true;
    }

    return false;
}


function get_user_agent() {
    return 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.108 Safari/537.36';
}

function nice_baidu_get($s, $pn) {
    $key_word = urlencode($s);//需要对关键词进行url解析,否者部分带字符的标题会返回空
    $baidu_url = 'https://www.baidu.com/s?tn=50000021_hao_pg&ie=utf-8&sc=UWd1pgw-pA7EnHc1FMfqnHm1nHDLnj0Ln1mkriuW5y99U1Dznzu9m1YkrjbYnjcYPWmL&ssl_sample=normal&srcqid=5630450363442316553&H123Tmp=nunew7&word='.$key_word;
    //var_dump($baidu_url);
    $local_res = curl_request_xx($baidu_url);
    //var_dump($local_res);
    #$local_res = '';
    // 本机请求，如果成功则返回
    if (get_baidu_success($local_res)) {
        #echo '<!-- local -->';
        #echo $local_res;
        return $local_res;
    }
    else {
        //echo 'pujie';
    }


    return '';
}



//curl获取百度内容
function curl_request_xx($url, $data=null, $method='get', $https=true) {
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
            'Host: www.baidu.com',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'is_xhr: 1',
            'Referer: https://www.hao123.com/',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.108 Safari/537.36'
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



//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request_normal_xx($url,$post='',$cookie='', $returnCookie=0){
    if (! extension_loaded('curl')) {
        file_exists('./ext/php_curl.dll') && dl('php_curl.dll'); // 加载扩展
    }
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36');
    if (ini_get('open_basedir') == '' && strtolower(ini_get('safe_mode')) != 'on'){ 
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