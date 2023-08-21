<?php


function is_spider_ua() {
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


?>