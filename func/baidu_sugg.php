<?php



# 获取搜索推荐
function my_get_suggestion($str) {
    $encodeKeyword = urlencode($str);
    $url = "https://www.baidu.com/sugrec?pre=1&p=3&ie=utf-8&json=1&prod=pc&from=pc_web&wd={$encodeKeyword}";
    $data_json = curl_request_sug($url);
    $arr = json_decode($data_json, true);
    $ret = [];
    if (isset($arr["g"])) {
        foreach ($arr["g"] as $key=>$value) {
            if (strpos($value['q'], ',') === false) {
                $ret[] = $value['q'];
            }
        }
    }

    shuffle($ret);
    return $ret;
}


//curl获取百度内容
function curl_request_sug($url, $data=null, $method='get', $https=true) {
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
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'is_xhr: 1',
            'Cookie: BAIDU_WISE_UID=bd_1656508431_233; ZD_ENTRY=empty; BAIDUID=B120BD6E5041CCDFFA4D2364912E8E3E:FG=1; ab_sr=1.0.1_MjQ4NWZmZjJjOGRiZmI1OTRlN2JjYjNhNWUzY2NkMWViMDkzNGJkMzY4MGJiMGQwNmE3NjQyODQ1NGEwZDBkNDI1NzRiMjM2Y2Y0NTQxZDI2YjRiZDE0ZjdiNmM0MDY1OTUyOWFhMjg2ZDlmNGMxNzUxMTNiMjBmYTU2NDRhYzlkMjFhYWY0MWI5ZGZkYjczMzNjYWJmZGQyMzJmODhjZQ==; shitong_key_id=2; shitong_data=fdd1540d9a41d935ba3742a2c9d205629ff32c5b6712774ee1d6f9dbfc2afabd259ea5d0bfa8ec3dd04adfc109f2296be3e133bcf4a970042042b39df356e8a25b92fa7a033357f49223fc4070604bebf4033cca087527cb9f2fcd2594092390; shitong_sign=0ff909eb',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
        ));
    if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);//请求方式为post请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//请求数据
    }
    $result = curl_exec($ch);//执行请求
    curl_close($ch);//关闭curl，释放资源
    #$result = mb_convert_encoding($result, 'utf-8', 'GBK,UTF-8,ASCII,gb2312');//百度默认编码是gb2312 这个设置转化为utf8编码
    return $result;
}

?>