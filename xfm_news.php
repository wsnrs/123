<?php

require_once('xfm_config.php');

require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_simple_cache.v.2.php');
require_once("func".DIRECTORY_SEPARATOR.'word_base.php');
require_once('mmlog.php');
require_once('xfm_images.php');



# 填充最新文章列表
function xfm_news_list($template) {
    global $keyword_list;
    global $read_count_begin;
    global $read_count_end;
    #$rand_read_count = rand($read_count_begin, $read_count_end);
    #$template = xfm_news_str_replace_once('{XFM_NEWS_NODE}', $value, $template);
    $spider_keyword_arr = get_keyword($keyword_list);
    // $template_images_arr = get_keyword('images.txt');
    $rand_image_list = get_local_images();

    while (strpos($template, '{XFM_COUNT_RAND}') !== false) {
        $count_rand = rand($read_count_begin, $read_count_end);
        $template = xfm_news_str_replace_once('{XFM_COUNT_RAND}', $count_rand, $template);
    }

    foreach ($spider_keyword_arr as $key => $value)
    {
        if (strpos($template, '{XFM_NEWS_NODE}') !== false) {
            // 关键词要先写缓存里
            xfm_put_keyword_cache($value);
            // 获取关键词短网址
            $short_url = short_md5($value);
            $domain_url = get_domain_url($short_url);
            $time_rand_week = get_rand_time_week();
            $rand_image = $rand_image_list[rand(0, count($rand_image_list)-1)];
            #$xfm_news_template = xfm_news_str_replace_once('{XFM_NEWS_NODE_SHORT_URL}', $short_url, $xfm_news_template);
            $template = xfm_news_str_replace_once('{XFM_NEWS_NODE_SHORT_URL}', $domain_url, $template);
            $template = xfm_news_str_replace_once('{XFM_NEWS_NODE_SHORT_URL}', $domain_url, $template);
            $template = xfm_news_str_replace_once('{XFM_NEWS_NODE}', $value, $template); // 这里要替换两次
            $template = xfm_news_str_replace_once('{XFM_NEWS_NODE}', $value, $template); // 这里要替换两次
            $template = xfm_news_str_replace_once('{XFM_TIME_WEEK_RAND}', $time_rand_week, $template);
            $template = xfm_news_str_replace_once('{XFM_IAMGE_RAND}', $rand_image, $template);
        }
    }

    return $template;
}


//echo $time_rand;




function get_keyword($file_name) {

    if (! file_exists($file_name)) {
        //var_dump($file_name);
        return '';
    }

    $data = file_get_contents($file_name);
    $data = remove_bom_str($data);
    $data = str_replace("\r", "\n", $data);
    $data = str_replace("\n\n", "\n", $data);
    $data = str_replace("\n\n", "\n", $data);
    $data = str_replace("\n", PHP_EOL, $data);
    $arr = explode(PHP_EOL, $data);
    $last = array_pop($arr);
    if (strlen($last)>1) {
        $arr[] = $last;
    }
    $new_arr = array();
    foreach ($arr as $key => $value) {
        $new_arr[$key]=trim($value, '->');
    }
    shuffle($new_arr);
    return $new_arr;
}

?>