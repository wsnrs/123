<?php
// 配置文件在这里面
require_once("xfm_config.php");
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
require_once('xfm_common_function.php');

#$new_keyword = get_all_menu();
#var_dump($new_keyword);
#echo '/*'.PHP_EOL;
#echo create_all_menu();
#echo '*/';

//echo create_all_menu();

//echo get_menu_by_template('common_menu_foot.html');

//     $sub_template = $template_dir.DIRECTORY_SEPARATOR.$tmp_arr[0];

//if (!file_exists($sub_template)) {
//    echo '模板错误：'. $tag;
//  }
//
//  $sub_template_str = file_get_contents($sub_template);






// 读取菜单模板
function get_menu_template_by_filename($filename) {
    global $template_dir;
    $template_file = $template_dir.DIRECTORY_SEPARATOR.$filename;
    return file_get_contents($template_file);
}


// 读取菜单模板
function get_menu_template() {
    global $template_dir;
    $template_file = $template_dir.DIRECTORY_SEPARATOR.'common_menu_top.html';
    return file_get_contents($template_file);
}


// 读取菜单模板
function get_foot_menu_template() {
    global $template_dir;
    $template_file = $template_dir.DIRECTORY_SEPARATOR.'common_menu_foot.html';
    return file_get_contents($template_file);
}


function get_menu() {
    $template = get_menu_template();
    while (strpos($template, '{TEXT}') !== false) {
        $href = 'xx';
        $text = 'xx';
        $template = xfm_news_str_replace_once('{HREF}', $href, $template);
        $template = xfm_news_str_replace_once('{TEXT}', $text, $template);
    }
}

// 获取一个随机栏目
function set_rand_menu($template) {
    $new_keyword = get_all_menu();
    $href_list = '';

    $keyword_index = 0;
    while (strpos($template, '{RAND_MENU') !== false) {
        $keyword = $new_keyword[$keyword_index];
        xfm_put_keyword_cache($keyword); // 写入缓存获取加密链接
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url, 3);
        $menu_tag = '{RAND_MENU'.strval($keyword_index+1).'}';
        $menu_tag_url = '{RAND_MENU_URL'.strval($keyword_index+1).'}';
        $template = str_replace($menu_tag_url, $domain_url, $template);
        $template = str_replace($menu_tag, $keyword, $template);

        $keyword_index = $keyword_index + 1; // 递增
    }


    return $template;

}

// 获取用户菜单
function create_footer_menu() {
    $new_keyword = get_all_menu();
    $href_list = '';

    $keyword_index = 0;
    $template = get_foot_menu_template();
    while (strpos($template, '{TEXT}') !== false) {
        $keyword = $new_keyword[$keyword_index];
        xfm_put_keyword_cache($keyword); // 写入缓存获取加密链接
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url, 3);
        $template = xfm_news_str_replace_once('{HREF}', $domain_url, $template);
        $template = xfm_news_str_replace_once('{TEXT}', $keyword, $template);

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


// 获取用户菜单
function get_menu_by_template($template_file) {
    global $sitename;
    $new_keyword = get_all_menu();
    $href_list = '';

    $keyword_index = 0;
    $template = get_menu_template_by_filename($template_file);
    $template = str_replace('{XFM_DOMAIN}', $_SERVER['HTTP_HOST'], $template);
    $template = str_replace('{XFM_SITENAME}', $sitename, $template);
    while (strpos($template, '{TEXT}') !== false) {
        $keyword = $new_keyword[$keyword_index];
        xfm_put_keyword_cache($keyword); // 写入缓存获取加密链接
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url, 3);
        #var_dump($domain_url);
        $template = xfm_news_str_replace_once('{HREF}', $domain_url, $template);
        $template = xfm_news_str_replace_once('{TEXT}', $keyword, $template);

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


// 获取用户菜单
function create_all_menu() {
    $new_keyword = get_all_menu();
    $href_list = '';

    $keyword_index = 0;
    $template = get_menu_template();
    while (strpos($template, '{TEXT}') !== false) {
        $keyword = $new_keyword[$keyword_index];
        xfm_put_keyword_cache($keyword); // 写入缓存获取加密链接
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url, 3);
        $template = xfm_news_str_replace_once('{HREF}', $domain_url, $template);
        $template = xfm_news_str_replace_once('{TEXT}', $keyword, $template);

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





# 读取所有菜单
function get_all_menu() {
    $menu_file = 'config'.DIRECTORY_SEPARATOR.'menu.txt';
    if (! file_exists($menu_file)) {
        echo 'menu.txt 不存在';
        exit;
    }
    $kws = txt2array($menu_file);
    if (count($kws)<2) {
        echo 'menu.txt 最少3个以上关键词';
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

# 链接格式
function format_href($url, $keyword) {
    return '<li><a href="'.$url.'" title="'.$keyword.'">'.$keyword.'</a></li>'.PHP_EOL;
}

?>