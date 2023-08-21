<?php
require_once("xfm_config.php");
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
#define('BASE_DOMAIN', 'http://'.$_SERVER['SERVER_NAME'].'/');
require_once('xfm_common_function.php');
#$new_keyword = get_all_user_tag();
#var_dump($new_keyword);
#echo '/*'.PHP_EOL;
//echo create_all_tag();
#echo '*/';

$all_tags = get_all_user_tag();
//echo get_tags_by_template('common_tag.html');



function create_all_tag() {
    $new_keyword = get_all_user_tag();
    $href_list = '';
    foreach($new_keyword as $key=>$keyword) {
        xfm_put_keyword_cache($keyword);
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url, 3);
        //echo strval($key+1).': ';
        //echo $domain_url;
        //echo '<br>'.PHP_EOL;
        #echo '<textarea style="width:300px;height:200px;">';
        $href_list .= format_href($domain_url, $keyword);
        #echo '</textarea>';
        #$tmp_self_url = get_domain_url('');
        #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
        #echo '<br>2:<br>';
        #var_dump('location: '. $tmp_self_url);
    }
    return $href_list;
}






// 读取菜单模板
function get_tag_template_by_filename($filename) {
    global $template_dir;
    $template_file = $template_dir.DIRECTORY_SEPARATOR.$filename;
    return file_get_contents($template_file);
}


// 获取用户菜单
function get_tags_by_template($template_file, $rand=false) {
    global $all_tags;
    //$new_keyword = get_all_user_tag();
    if ($rand) {
        shuffle($all_tags);
        //shuffle($new_keyword);
    }

    $href_list = '';

    $keyword_index = 0;
    $template = get_tag_template_by_filename($template_file);
    while (strpos($template, '{TEXT}') !== false) {
        //$keyword = $all_tags[$keyword_index];
        $keyword = array_shift($all_tags);
        xfm_put_keyword_cache($keyword); // 写入缓存获取加密链接
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url, 3);
        $template = xfm_news_str_replace_once('{HREF}', $domain_url, $template);
        $template = xfm_news_str_replace_once('{HREF2}', $domain_url, $template);
        $template = xfm_news_str_replace_once('{TEXT}', $keyword, $template);
        $template = xfm_news_str_replace_once('{TEXT2}', $keyword, $template);
        $template = xfm_news_str_replace_once('{TEXT3}', $keyword, $template);
        $keyword_index = $keyword_index + 1; // 递增
    }

    /*
    foreach($new_keyword as $key=>$keyword) {
        xfm_put_keyword_cache($keyword);
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url);
        //var_dump($domain_url);
        //echo strval($key+1).': ';
        //echo $domain_url;
        //echo '<br>'.PHP_EOL;
        #echo '<textarea style="width:300px;height:200px;">';
        $href_list .= format_href($domain_url, $keyword);
        #echo '</textarea>';
        #$tmp_self_url = get_domain_url('');
        #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
        #echo '<br>2:<br>';
        #var_dump('location: '. $tmp_self_url);
    }
    */
    return $template;
    //return $href_list;
}


/*
function push_one_keyword($keyword) {
    xfm_put_keyword_cache($keyword);
    // 获取关键词短网址
    $short_url = short_md5($keyword);
    $domain_url = get_domain_url($short_url);
    echo '1:<br>';
    var_dump($domain_url);
    #$tmp_self_url = get_domain_url('');
    #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
    #echo '<br>2:<br>';
    #var_dump('location: '. $tmp_self_url);
    #return $tmp_self_url;
}
*/

# 读取所有TAG
function get_all_user_tag() {
    $tag_file = 'config'.DIRECTORY_SEPARATOR.'tags.txt';
    if (! file_exists($tag_file)) {
        echo 'tags.txt 不存在';
        exit;
    }
    $kws = txt2array($tag_file);
    if (count($kws)<2) {
        echo 'tags.txt 最少3个以上关键词';
        exit;
    }
    return $kws;
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


?>