<?php
// 配置文件在这里面
require_once("xfm_config.php");


$cur_page_url = $http_header.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // 当前页面链接 {XFM_PAGE_URL}
//echo '<!-- index.php 7 '.$_SERVER['REQUEST_URI'].' -->';

$cur_time = date($date_format);


require_once '3rd'.DIRECTORY_SEPARATOR.'phpQuery.php';
require_once '3rd'.DIRECTORY_SEPARATOR.'short_url.php';
require_once 'func'.DIRECTORY_SEPARATOR.'article_punctuation.php'; // 排版
require_once 'black_list.php';          // 黑名单
require_once 'mmlog.php';               // csv日志
require_once 'xfm_images.php';          // AI图片生成
require_once 'func'.DIRECTORY_SEPARATOR.'spider_info.php';         // 判断是否蜘蛛
require_once 'xfm_common_function.php'; // 函数
require_once 'xfm_template.php';        // 模板
require_once 'sensitive_words.php';     // 敏感词过滤
#require_once 'xfm_debug.php';           // 调试信息
@ini_set('default_charest','utf-8');

$domain = $_SERVER['HTTP_HOST'];
$base_href = get_self_url();
$base_tag = get_base_tag();



# 计算首页<base 的地址，主要用于目录
function get_base_tag() {
    $self_dir = dirname($_SERVER["PHP_SELF"]);
    if (strlen($self_dir)>1) { // 安装在目录
        $self_dir .= '/';
    }
    $base_href = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].$self_dir;
    //var_dump($_SERVER);
    //var_dump($base_href);
    return PHP_EOL.'<base href="'.$base_href.'" />';
}

$pn_base = rand(0, 5);

$short_url = isset($_GET['title'])?$_GET['title']:''; // 短网址 例如 index?title=3829292 防止刷关键词导致服务器奔溃
$pn = isset($_GET['pn'])?intval($_GET['pn']):$pn_base*10;


// 黑名单，防止把CPU刷爆
if (is_black_list()) {
    log7('black_log', $short_url);
    echo $cur_page_url;
    exit;
}


$cache_only = false;
# 如果是百度蜘蛛，添加白名单关键词
$spider_info = is_spider_ua();
if ($spider_info) {
    log7($spider_info.'_spider', $short_url);
    #add_new_keyword($s, '');
}
else if (cache_only_ua()) { // 防止浪费系统只有
    log7('cache_only', $short_url);
    $cache_only = true;
}
else {
    // 普通用户没有缓存也跳转
    log7('user_log', $short_url);
}


// 404 页面处理。
if (count($_GET) > 0) {
    //var_dump($_GET);
    foreach($_GET as $key=>$value) {
        if (strpos($key, '/') !== false) {
            //var_dump($key);
            //header('location: /user_push.php');
        }
    }
}
else {
    //echo 'goooo';
}
//exit;




/////////////////// 404 页面处理，用来处理新买域名404问题
// 这个处理起来有点绕，能不用最好不用
//echo '<!-- QUERY_STRING:'. $_SERVER["QUERY_STRING"].' -->'.PHP_EOL;
//echo '<!-- REQUEST_URI:'. $_SERVER["REQUEST_URI"].' -->'.PHP_EOL;
//echo '<!-- SCRIPT_NAME:'. $_SERVER["SCRIPT_NAME"].' -->'.PHP_EOL;
//echo '<!-- str_replace:'.str_replace($_SERVER["REQUEST_URI"], '', $_SERVER["SCRIPT_NAME"]).' -->'.PHP_EOL;
#var_dump($_SERVER);
if (file_exists('super_404.php')) {
    if ($short_url === '' && isset($_SERVER['REQUEST_URI'])) {
        //var_dump($_SERVER['REQUEST_URI']);
        $srv_srcipt = $_SERVER["SCRIPT_NAME"];
        $srv_request = str_replace($_SERVER["REQUEST_URI"], '', $_SERVER["SCRIPT_NAME"]);
        if ('index.php' !== $srv_request) {
        //if ($_SERVER['REQUEST_URI'] !== '/') {
            require_once('super_404.php');
            require_once('xfm_simple_cache.v.2.php');
            $keyword = get_404_keyword();
            xfm_put_keyword_cache($keyword); // 要写入缓存
            $short_url = short_md5($keyword);
            $s = $keyword;
        }
        else {
        }
    }
    else {
        //echo '<!-- no 404 -->'.PHP_EOL;
    }
}
/////////////////// 404 页面处理，用来处理新买域名404问题




// 有缓存，就不执行后面的代码，节约资源
if ($short_url !== '') {
    // 如果关键词缓存存在，则会显示缓存页面
    require_once ("article_cache.php");
    if ($xfm_is_cache) {
        show_html_comment('cached');
        exit;
    }
    else {
        show_html_comment('no cached');

        //if ($cache_only) {
        //    echo '404. cached only.';
        //    exit;
        //}
    }
}




# 访问首页的情况下，$short_url 为空
require_once ('user_menu.php');
require_once ('user_tag.php');
/*
get_menu_template_by_filename('common_menu_top.html');
get_menu_template_by_filename('common_menu_foot.html');
*/
$user_tags = get_tags_by_template('common_tag.html');
$user_tags2 = get_tags_by_template('common_tag2.html'); // 文章里的TAG
$user_tags3 = get_tags_by_template('common_tag3.html'); // 文章里的TAG

$top_menu = get_menu_by_template('common_menu_top.html');//create_all_menu();
$foot_menu = get_menu_by_template('common_menu_foot.html');



# 不带文章参数默认为首页
if ($short_url === '') {
    $spider_keyword_arr = get_keyword($keyword_list);
    require_once("xfm_tag_parser.php");
    if (! show_index('index_template.html')) {
        header('location: config.php');
    }
    else {
        #echo 'show index ok.';
    }
    exit;
}

$template = get_template('article_template.html'); // 加载模板
$template_len = strlen($template); // 模板大小
/*
# 如果是百度蜘蛛，添加白名单关键词
if (is_spider_ua()) {
}
else if (is_black_list()) {
    header('location:https://www.baidu.com/s?ie=utf-8&wd=%E4%BC%AA%E5%8E%9F%E5%88%9B');
}
else {
    header('location:https://www.baidu.com/s?ie=utf-8&wd=%E4%BC%AA%E5%8E%9F%E5%88%9B');
}

*/

# $t2 = microtime(true); //获取程序1，开始的时间
# echo '<!-- 耗时'.round($t2-$t1,3).'秒 -->';
#var_dump($s);
$abs_arr = array();
$relative_keyword = array();
$imgs = array();
$tags = array();
$title_arr = array();
$is_ai_v12 = false;

# 是否存在v12版本
if (file_exists('ai_article_v12.php')) {
    require_once('ai_article_v12.php');
    list($abs_arr, $relative_keyword) = get_article_v12($s, $pn);
}
#var_dump('xxxxxxxxxxxxxx');
/*
var_dump($abs_arr);
var_dump($relative_keyword);
var_dump($imgs);
var_dump($title_arr);
var_dump($ai_tags);
exit;*/
require_once('ai_article.php');
if (count($abs_arr) > 200) {
    #var_dump('avvv12');
    $is_ai_v12 = true;
    $article = create_ai_article_v2($s, $abs_arr, $relative_keyword, [], []);
    //var_dump($article);
    //var_dump('xxxxxxxx');
}
else {
    require_once "ai_article_v1.php";
    // 获取文章内容
    list($abs_arr, $relative_keyword) = get_article_v1($s, $pn);
    $article = create_ai_article_v1($s, $abs_arr, $relative_keyword);
    #var_dump('bbbbbbbb');
}


# var_dump($relative_keyword);


// 标题信息
// 相关关键词存在3个以上
if (count($relative_keyword) > 3) {
    if (! $is_old_title) {
        # $title = $s.$relative_keyword[1].$title_separator.$relative_keyword[0];// . '_' . $relative_keyword[1];
        // 防止关键词重复
        if ($s !== $relative_keyword[1]) {
            $title = $s.$title_separator.$relative_keyword[1];// . '_' . $relative_keyword[1];
        }
        else {
            $title = $s.$title_separator.$relative_keyword[2];// . '_' . $relative_keyword[1];
        }
    }
    else {
        $title = $s.'cc';
    }
}
else {
    #var_dump($relative_keyword);
    $title = $s.'';
}


// 描述信息
$description = isset($abs_arr[0]['abstract']) ? strip_tags($abs_arr[0]['abstract']) : '';
if ($description === '') {
    $description = isset($abs_arr[0]) ? strip_tags($abs_arr[0]) : $s;
}
$description = str_replace('"', ' ', $description);


#$ai_article = s
#var_dump($abs_arr);

#var_dump($new_arr);
#echo $article;
#echo '666'.'<HR>'.PHP_EOL;

#$word_arr = fix_punctuaction($article);
#$article = $word_arr[0];


if ($article !== '') {
    require_once 'ai_neural_nodes.php';
    $article = article_filter($article);
    $title = aritcle_sensitive_filter($title);
}

$spider_keyword_arr = get_keyword($keyword_list);
require_once("xfm_tag_parser.php");


// $weiyuanchuang 是否伪原创
$wyc_api_exists = false;
if (isset($GLOBALS["weiyuanchuang"]) && $GLOBALS["weiyuanchuang"] !== '') {
    //var_dump($article);
    if ($article !== '') {
        $article = curl_request($weiyuanchuang, array('wenzhang' => $article));
    }
    //echo '<hr>';
    //var_dump($article);
}

#$article = '';
$article = $article===''?'此文章处于编辑状态':$article;


require_once('text_2_html.php');        // 文本转HTML
require_once('xfm_user_href.php');      // 自动加锚文本
$content_src = clear_content($article); // 过滤特殊字符
$ai_article_html = my_text2html($s, $content_src);

$href_arr = my_get_rand_keyword('baidu.txt');   // 读取锚文本数据
$href_format = '<a href="{XFM_SUB_URL}" target="_blank">{XFM_SUB_KEYWORD}</a>'; // 锚文本格式
$ai_article_html = my_replace_href($ai_article_html, $href_arr, $href_format); // 添加锚文本

$images = '';


$article_arr = explode("\n", $article);
$question_1 = clear_content($article_arr[0]);
$question_1 = '<p>'.$question_1.'</p>';
$question_1 .= $images;



$answer_1 = isset($article_arr[1])?ai_para($article_arr[1]):'';
$answer_2 = isset($article_arr[2])?ai_para($article_arr[2]):'';
$answer_3 = isset($article_arr[3])?ai_para($article_arr[3]):'';
$answer_4 = isset($article_arr[4])?ai_para($article_arr[4]):'';
$answer_5 = isset($article_arr[5])?ai_para($article_arr[5]):'';
$answer_6 = isset($article_arr[6])?ai_para($article_arr[6]):'';


// 组合成段落
function ai_para($str) {
    return '<p>'.$str.'</p>'.PHP_EOL;
}


$template = update_index($template);
$template = str_replace(array(
    '{QUESTION}',
    '{ANSWER_1}',
    '{ANSWER_2}',
    '{ANSWER_3}',
    '{ANSWER_4}',
    '{ANSWER_5}',
    '{ANSWER_6}'
    ), array(
    $question_1,
    $answer_1,
    $answer_2,
    $answer_3,
    $answer_4,
    $answer_5,
    $answer_6
    ), $template);

$page_table = '';
$relative_table = '';
$result_table = '';


#$template_news = file_get_contents(TMP_DIR.DIRECTORY_SEPARATOR.'article_template_news.html');
#$template_rewards = file_get_contents(TMP_DIR.DIRECTORY_SEPARATOR.'article_template_rewards.html');
$template_relatives = file_get_contents(TMP_DIR.DIRECTORY_SEPARATOR.'article_template_relatives.html');
require_once('xfm_news.php');
$template = xfm_news_list($template);


// 目前最多10个
$relative1 = isset($relative_keyword[0])?$relative_keyword[0]:$spider_keyword_arr[0];
$relative2 = isset($relative_keyword[1])?$relative_keyword[1]:$spider_keyword_arr[1];
$relative3 = isset($relative_keyword[2])?$relative_keyword[2]:$spider_keyword_arr[2];
$relative4 = isset($relative_keyword[3])?$relative_keyword[3]:$spider_keyword_arr[3];
$relative5 = isset($relative_keyword[4])?$relative_keyword[4]:$spider_keyword_arr[4];
$relative6 = isset($relative_keyword[5])?$relative_keyword[5]:$spider_keyword_arr[5];
$relative7 = isset($relative_keyword[6])?$relative_keyword[6]:$spider_keyword_arr[6];
$relative8 = isset($relative_keyword[7])?$relative_keyword[7]:$spider_keyword_arr[7];
$relative9 = isset($relative_keyword[8])?$relative_keyword[8]:$spider_keyword_arr[9];
$relative10 = isset($relative_keyword[9])?$relative_keyword[9]:$spider_keyword_arr[0];



$template = str_replace(
    array('{XFM_RELATIVE1}',
        '{XFM_RELATIVE2}',
        '{XFM_RELATIVE3}',
        '{XFM_RELATIVE4}',
        '{XFM_RELATIVE5}',
        '{XFM_RELATIVE6}',
        '{XFM_RELATIVE7}',
        '{XFM_RELATIVE8}',
        '{XFM_RELATIVE9}',
        '{XFM_RELATIVE10}'
        ), 
    array($relative1,
        $relative2,
        $relative3,
        $relative4,
        $relative5,
        $relative6,
        $relative7,
        $relative8,
        $relative9,
        $relative10,
    ),
    $template);

$template = str_replace(
    array('{XFM_RELATIVE_SHORT_URL1}',
        '{XFM_RELATIVE_SHORT_URL2}',
        '{XFM_RELATIVE_SHORT_URL3}',
        '{XFM_RELATIVE_SHORT_URL4}',
        '{XFM_RELATIVE_SHORT_URL5}',
        '{XFM_RELATIVE_SHORT_URL6}',
        '{XFM_RELATIVE_SHORT_URL7}',
        '{XFM_RELATIVE_SHORT_URL8}',
        '{XFM_RELATIVE_SHORT_URL9}',
        '{XFM_RELATIVE_SHORT_URL10}',
        ), 
    array(get_domain_url(short_md5($relative1)),
        get_domain_url(short_md5($relative2)),
        get_domain_url(short_md5($relative3)),
        get_domain_url(short_md5($relative4)),
        get_domain_url(short_md5($relative5)),
        get_domain_url(short_md5($relative6)),
        get_domain_url(short_md5($relative7)),
        get_domain_url(short_md5($relative8)),
        get_domain_url(short_md5($relative9)),
        get_domain_url(short_md5($relative10))),
    $template);


// 写入关键词缓存
xfm_put_keyword_cache($relative1);
xfm_put_keyword_cache($relative2);
xfm_put_keyword_cache($relative3);
xfm_put_keyword_cache($relative4);
xfm_put_keyword_cache($relative5);
xfm_put_keyword_cache($relative6);
xfm_put_keyword_cache($relative7);
xfm_put_keyword_cache($relative8);
xfm_put_keyword_cache($relative9);
xfm_put_keyword_cache($relative10);


# 插件处理
require('plugins'.DIRECTORY_SEPARATOR.'xfm_plugins.php');
$article_info = array('article'=>$ai_article_html, 'title'=>$title);
$article_info_plugin = xfm_plugin($article_info);
$ai_article_html = $article_info_plugin['article'];



$xfm_news_template = '';
$rand_read_count = rand($read_count_begin, $read_count_end);

$article_ok = str_replace(
    array(
    '{XFM_TITLE}',         /* 1 */
    '{XFM_KEYWORDS}',       /* 2 */
    '{XFM_SITENAME}',      /* 3 */
    '{XFM_DOMAIN}',        /* 4 */
    '{XFM_TIME}',          /* 5 */
    '{XFM_ARTICLE}',       /* 6 */
    '{XFM_RELATIVE}',      /* 7 */
    '{XFM_PAGE}',          /* 8 */
    '{XFM_NEWS_LIST}',     /* 9 */
    '{XFM_RELATIVES}',     /* 10 */
    '{XFM_DESCRIPTION}',    /* 11 */
    '{XFM_TOP_MENU}',        /* 12 */
    '{XFM_PAGE_URL}',       /* 13 */
    '{XFM_FOOT_MENU}',      /* 14 */
    '{USER_TAGS3}',         /* 15 */
    '{USER_TAGS2}',         /* 16 */
    '{USER_TAGS}',          /* 17 */
    '{RAND_COUNT}',         /* 18 */
    '{BASE_URL}',           /* 19 */
    '{BASE_TAG}',           /* 20 */
    ),
    array(
    $title,                 /* 1 */
    $s,                     /* 2 */
    $sitename,              /* 3 */
    $domain,                /* 4 */
    $cur_time,              /* 5 */
    $ai_article_html,       /* 6 */
    $relative_table,        /* 7 */
    $page_table,            /* 8 */
    $xfm_news_template,     /* 9 */
    $template_relatives,    /* 10 */
    $description,           /* 11 */
    $top_menu,              /* 12 */
    $cur_page_url,          /* 13 */
    $foot_menu,             /* 14 */
    $user_tags3,            /* 15 */
    $user_tags2,            /* 16 */
    $user_tags,             /* 17 */
    $rand_read_count,       /* 18 */
    $base_href,             /* 19 */
    $base_tag,              /* 20 */
    ),
    $template);

#$domain = 'http://127.0.0.1:8080'.$_SERVER["SCRIPT_NAME"];
#$domain .= '?title=';
$tmp_self_url = get_domain_url($short_url);
#$article_ok = str_replace($tmp_self_url, BASE_DOMAIN, $article_ok);

// 更新到最新列表
xfm_update_index_list($description, $title, $tmp_self_url, $cur_time);

// 如果首页链接写满了，就不需要继续写了，写满时候 18个。

/*
if (! file_exists(INDEX_LIST_LOCK)) {
    xfm_put_index_list($description, $title, $tmp_self_url, $cur_time);
}
else {
    xfm_put_today_list($description, $title, $tmp_self_url, $cur_time);
}*/

// 敏感词过滤
require_once('sensitive_words.php');
$article_ok = aritcle_filter($article_ok);
$article_ok = template_config_replace($article_ok); // TDK信息
$article_ok = set_rand_menu($article_ok); // 文章栏目
#echo $ai_article_html;
// 由于部分模板带文章内链，如果生成数量太少则无法显示内链，所以要生成20个以上才正式缓存
//if (count($news_list) > 20) {
xfm_put_cache($s, $article_ok); // 保存缓存
//}

@file_put_contents($shuju_dir.DIRECTORY_SEPARATOR.'xfm_cache_new_log.txt', $xfm_cache_kw.PHP_EOL, FILE_APPEND);
@file_put_contents($shuju_dir.DIRECTORY_SEPARATOR.'xfm_all_url.txt', $cur_page_url.PHP_EOL, FILE_APPEND);

echo $article_ok;


# 写入每日更新文章列表
function xfm_put_today_list($_description, $_title, $_url, $_time) {
    global $shuju_dir;
    $_description = str_replace("\r", '', $_description);
    $_description = str_replace("\n", '', $_description);
    $date_file = date('Y-m-d');
    $data = serialize(array('title'=>$_title, 'description'=>$_description, 'url'=>$_url, 'time'=>$_time));
    $data .= PHP_EOL;
    file_put_contents($shuju_dir.DIRECTORY_SEPARATOR.$date_file.'_list.txt', $data, FILE_APPEND|LOCK_EX);
}


# 写入首页文章列表
function xfm_put_index_list($_description, $_title, $_url, $_time) {
    $_description = str_replace("\r", '', $_description);
    $_description = str_replace("\n", '', $_description);
    $data = serialize(array('title'=>$_title, 'description'=>$_description, 'url'=>$_url, 'time'=>$_time));
    $data .= PHP_EOL;
    file_put_contents(get_index_list_file(), $data, FILE_APPEND|LOCK_EX);
}


# 首页文章列表文件目录
function get_index_list_file() {
    global $shuju_dir;
    $index_list_file = $shuju_dir.DIRECTORY_SEPARATOR.'index_list.txt';
    return $index_list_file;
}

# 更新首页文章列表
function xfm_update_index_list($_description, $_title, $_url, $_time) {
    $index_list_file = get_index_list_file();
    $data_arr = xfm_txt_to_array($index_list_file);
    if (count($data_arr) <= INDEX_MAX_LIST) {
        xfm_put_index_list($_description, $_title, $_url, $_time);
    }
    else {
        array_shift($data_arr);
        $_description = str_replace("\r", '', $_description);
        $_description = str_replace("\n", '', $_description);
        $data = serialize(array('title'=>$_title, 'description'=>$_description, 'url'=>$_url, 'time'=>$_time));
        #var_dump($data_arr);
        $data_arr[] = $data;
        file_put_contents($index_list_file, implode($data_arr, PHP_EOL), LOCK_EX);
    }
}

// 获取写作素材
function get_article($s, $pn) {

    require_once('proxy_curl_get.php');

    #$key_word = urlencode($s);//需要对关键词进行url解析,否者部分带字符的标题会返回空
    #$url = 'https://www.baidu.com/s?ie=UTF-8&rn=20&pn='.$pn.'&wd='.$key_word;

    $res = nice_baidu_get($s, $pn);
    #var_dump($res);
    #$res = curl_request($url);
    //$html = phpQuery::newDocumentFile("https://segmentfault.com/tags");
    $html = phpQuery::newDocumentHTML($res);
    $resultList = pq(".result"); //获取标签为a的所有对象$(".tag")

    //var_dump($hrefList);
    $result_index = 1;
    $result_table = '';

    $abs_arr = array();

    $relativeList = pq("#rs");
    //echo '<h5>相关搜索</h5>';
    $relativ_arr = array();
    foreach ($relativeList as $key => $relative) {
        $hrefList = pq($relative)->find('a');
        foreach ($hrefList as $key => $href) {
            $word = pq($href)->text();
            #$url = my_relative_url($word);
            $relativ_arr[] = str_replace(array('?'), '', $word);//'<a href="'.$url.'" target="_blank">'.$word.'</a>';
            //echo '<a href="'.$url.'" target="_blank">'.$word;
            //echo '</a>';
            //echo $href->getAttribute('href');
            //echo ' --- ';
        }
    }

    foreach ($resultList as $result) {
        $title = pq($result)->find('.t > a');
        $url = '';
        foreach ($title as $key => $value) {
            $url = $value->getAttribute('href');
        }

        $html = $title->html();
        $my_bd_url = my_baidu_url($url, $html);
        //echo '<br>'.PHP_EOL;
        $abstract = pq($result)->find('.c-abstract');
        $abs_text = $abstract->text();
        #var_dump($abs_text);
        if (strpos($abs_text, '日'.chr(194) . chr(160))) {
            $abs_base = explode('日'.chr(194) . chr(160), $abs_text);
        }
        else if (strpos($abs_text, '前'.chr(194) . chr(160))) {
            $abs_base = explode('前'.chr(194) . chr(160), $abs_text);
        }
        else {
            $abs_base = explode('前'.chr(194) . chr(160), $abs_text);
        }
        #var_dump($abs_base);
        #var_dump(count($abs_base));
        #if (count($abs_base) === 1) {
            #var_dump($abs_base);
        #}
        if (count($abs_base)>1) {
            #var_dump($abs_base);
            $abs_text = $abs_base[1];
        }
        else {
            $abs_text = $abs_base[0];
        }

        $abs_text = 'xxbxx'.$abs_text;
        $abs_text = str_replace('xxbxx-', '', $abs_text);
        $abs_text = str_replace('xxbxx', '', $abs_text);
        $abs_text = str_replace(chr(194) . chr(160), "", $abs_text); // 某些恶心的字符
        #$abs_text = preg_replace("/\s+/","",$abs_text);
        #$abs_text = str_replace('.', '', $abs_text);
        #$abs_text = urlencode($abs_text);
        if (strlen($abs_text)>20) {
            $abs_arr[] = $abs_text;
        }
        //echo '<hr>'.PHP_EOL;*/

        #$result_table .= $result_index.$abs_text.'<br>'.PHP_EOL;
        #$result_table .= $abs_text.'<br>'.PHP_EOL;

        $result_index += 1;
    }

    shuffle($abs_arr);
    shuffle($relativ_arr);
    return array($abs_arr, $relativ_arr);

}



function my_baidu_url($baidu_url, $html) {
    return str_replace(array("\r", "\n"), array('',''),'<a itemprop="url" href="'.$baidu_url.'" rel="external nofollow noreferrer" target="_blank" title="73">'.$html.'</a>');
}

function my_page_url($url, $s) {
    global $base_href;
    $arr = explode('&', $url);
    return $base_href.'?s='.$s.'&'.$arr[1];
}


//echo '<hr>';
//echo $res;
/*
$reach_word = substr($res,strpos($res, '<div id="rs"><div class="tt">相关搜索'),strpos($res, '<div id="page" >')-strpos($res, '<div id="rs"><div class="tt">相关搜索') );//截取需要的内容
 
preg_match('/<a.*?">(.*?)<\/a>/', $reach_word,$match);//正则匹配第一个搜索词
$reach_word = @$match[1];
*/

//$cur_time = date($date_format);
/*
echo str_replace(
    array(
    '{XFM_KEYWORD}',
    '{XFM_SITENAME}',
    '{XFM_DOMAIN}',
    '{XFM_TIME}',
    '{XFM_RESULT}',
    '{XFM_RELATIVE}',
    '{XFM_PAGE}'
    ), 
    array(
    $s,
    $sitename,
    $domain,
    $cur_time,
    $result_table,
    $relative_table,
    $page_table
    ),
    $template);
*/



//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url,$post='',$cookie='', $returnCookie=0){
    if (! extension_loaded('curl')) {
        file_exists('./ext/php_curl.dll') && dl('php_curl.dll'); // 加载扩展
    }
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
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




# 显示首页
function show_index($template_file) {
    global $top_menu;
    global $foot_menu;
    global $cur_time;
    global $sitename;
    global $user_tags;
    global $user_tags2;
    global $user_tags3;
    global $domain;
    global $news_list;//  = get_index_news_list(); // 文章列表
    global $index_max_list;
    global $read_count_begin;
    global $read_count_end;


    $template = get_template($template_file); // 加载模板

    if (!isset($news_list) || count($news_list)<1) {
        echo 'bbbbbbbb';
        return false;
    }


    // 写满18个首页链接，就不继续写了。
    //if (count($news_list) > $index_max_list) {
        //file_put_contents(INDEX_LIST_LOCK, '1');
    //}

    $index = 0;
    $template = update_index($template); // 填充用户自定义TAG


    while (strpos($template, '{XFM_COUNT_RAND}') !== false) {
        $count_rand = rand($read_count_begin, $read_count_end);
        $template = xfm_news_str_replace_once('{XFM_COUNT_RAND}', $count_rand, $template);
    }

    while (strpos($template, '{XFM_NEWS_NODE}') !== false) {
        if (! isset($news_list[$index])) {
            break;
        }
        $news_info = unserialize($news_list[$index]);
        #var_dump($news_info);
        //$template = xfm_news_str_replace_once('{XFM_NEWS_NODE_SHORT_URL2}', $news_info['url'], $template); // 需要替换两次
        //$template = xfm_news_str_replace_once('{XFM_NEWS_NODE_SHORT_URL2}', $news_info['url'], $template); // 需要替换两次
        //$template = xfm_news_str_replace_once('{XFM_NEWS_NODE2}', $news_info['title'], $template); // 需要替换两次
        //$template = xfm_news_str_replace_once('{XFM_NEWS_NODE2}', $news_info['title'], $template); // 需要替换两次
        #$template = xfm_news_str_replace_once('{XFM_NEWS_NODE_DESCRIPTION2}', $news_info['description'], $template);
        #$template = xfm_news_str_replace_once('{XFM_NEWS_NODE_DESCRIPTION2}', $news_info['description'], $template);

        #$template = xfm_news_str_replace_once('{XFM_NEWS_NODE_SHORT_URL1}', $news_info['url'], $template); // 需要替换两次
        #$template = xfm_news_str_replace_once('{XFM_NEWS_NODE1}', $news_info['title'], $template); // 需要替换两次
        #$template = xfm_news_str_replace_once('{XFM_NEWS_NODE_DESCRIPTION1}', $news_info['description'], $template);

        $index ++;
    }

    $rand_read_count = rand($read_count_begin, $read_count_end);
    $template = str_replace('{RAND_COUNT}',  $rand_read_count, $template);
    $template = str_replace('{XFM_DOMAIN}', $domain, $template);
    $template = str_replace('{XFM_SITENAME}', $sitename, $template);
    $template = str_replace('{XFM_TIME}', $cur_time, $template);
    $template = str_replace('{XFM_TOP_MENU}', $top_menu, $template);
    $template = str_replace('{XFM_FOOT_MENU}', $foot_menu, $template);
    $template = str_replace('{USER_TAGS2}', $user_tags2, $template);
    $template = str_replace('{USER_TAGS3}', $user_tags3, $template);
    $template = str_replace('{USER_TAGS}', $user_tags, $template);
    $template = str_replace('{XFM_HOME_TITLE}', easy_get_config('home_title_txt'), $template);
    $template = str_replace('{XFM_HOME_DESCRIPTION}', easy_get_config('home_description_txt'), $template);
    $template = str_replace('{XFM_HOME_KEYWORDS}', easy_get_config('home_keyword_txt'), $template);

    $template = set_rand_menu($template); // 文章栏目
    #var_dump($template);
    echo $template;
    #var_dump('xxxxxxxx');
    return true;
}


function template_config_replace($template) {
    $template = str_replace('{XFM_HOME_TITLE}', easy_get_config('home_title_txt'), $template);
    $template = str_replace('{XFM_HOME_DESCRIPTION}', easy_get_config('home_description_txt'), $template);
    $template = str_replace('{XFM_HOME_KEYWORDS}', easy_get_config('home_keyword_txt'), $template);

    return $template;
}


?>