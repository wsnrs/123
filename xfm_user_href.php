<?php
require_once("func".DIRECTORY_SEPARATOR.'word_base.php');

/*
$article = '懒癌福音！文章自动编..一楼敬给度娘。伪原创是在原创的基础上修改文章，然后让搜索引擎觉得你是一篇原创文章。那么，你就成功了。个人觉得伪原创的话，就小猫智能写作，使用大数据和人工智能技术，提供作者写作方向的准确洞察，输入关键词自动生成文章并基于作者的自收集内容和订阅的图书馆提供智能写作服务。作为一种智能写作。';

#var_dump(my_get_rand_keyword('baidu.txt'));
$href_arr = my_get_rand_keyword('baidu.txt');
#var_dump($href_arr);
$href_format = '<a href="{XFM_SUB_URL}" target="_blank">{XFM_SUB_KEYWORD}</a>'; // 锚文本格式
var_dump(my_replace_href($article, $href_arr, $href_format));
*/

function my_replace_href($article, $href_arr, $href_format) {
    $anti_repeat = array();
    foreach ($href_arr as $key => $value) {
        $keyword = $value[0];
        $url = $value[1];
        $href = str_replace(array('{XFM_SUB_URL}', '{XFM_SUB_KEYWORD}'), array($url, $keyword), $href_format);
        $uuid = md5($href.$key);
        $anti_repeat[$uuid] = $href;
        #var_dump($href);
        $article = xfm_str_replace_once($keyword, $uuid, $article);
        #$article = str_replace($keyword, $href, $article);
    }

    foreach ($anti_repeat as $key => $value) {
        $article = xfm_str_replace_once($key, $value, $article);
    }

    return $article;
}

function my_get_rand_keyword($href_filename) {
    $keywords = file_get_contents($href_filename);
    $keywords = remove_bom_str($keywords);
    $keywords = api_fix_newline($keywords);

    $kw_arr = explode("\n", $keywords);

    $ret_arr = array();
    foreach ($kw_arr as $key => $value) {
        if (strpos($value, '`') !== false) {
            $ret_arr[] = explode('`', $value);
        }
    }

    return $ret_arr;
}

?>