<?php


#var_dump(is_spider_ua());

# 支持统计的蜘蛛类型
function get_spider_type() {
    $spider_list = array(
        'Baiduspider'=>'baidu',
        'google.com'=>'google',
        'bingbot'=>'bing',
        'sogou.com'=>'sogou');

    return $spider_list;
}


// 获取蜘蛛信息
function is_spider_ua() {

    $spider_list = get_spider_type();

    foreach ($spider_list as $key=>$value) {
        $php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
        if (strpos($php_ua, $key) !== false) {
            return $value;
        }
    }

    return false;
}

// 搜狗蜘蛛
function is_sogou_spider() {
    $php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
    if (strpos($php_ua, 'sogou.com') !== false) {
        return true;
    }
    return false;
}


// 百度蜘蛛
function is_baidu_spider() {
    $php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
    if (strpos($php_ua, 'Baiduspider') !== false) {
        return true;
    }
    return false;
}


// 谷歌蜘蛛
function is_google_spider() {
    $php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
    if (strpos($php_ua, 'google.com') !== false) {
        return true;
    }
    return false;
}


// bing bingbot
function is_bing_spider() {
    $php_ua = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
    if (strpos($php_ua, 'bingbot') !== false) {
        return true;
    }
    return false;
}

?>