<?php
require_once('xfm_nice_img.php');

if (! isset($GLOBALS['ascii_code'])) {
    $ascii_code = true;
}

if (! function_exists('xfm_str_replace_once')) {
    require(dirname(__DIR__).DIRECTORY_SEPARATOR.'word_base.php');
}

// 测试测试测试测试 测试测试测试测试 测试测试测试测试
/*
$article = '12月20日，税务部门发布通报，头部网络主播黄薇（网名：薇娅）偷逃税被罚共计13.41亿元。调查显示，黄薇在2019年至2020年期间，通过隐匿个人收入、虚构业务转换收入性质虚假申报等方式偷逃税款6.43亿元，其他少缴税款0.6亿元，依法对黄薇作出税务行政处理处罚决定，追缴税款、加收滞纳金并处罚款，共计13.41亿元。
21日，中纪委评薇娅偷逃税被罚：最根本原因还是贪婪，钱再多都不嫌多。现在连理论上都已经不存在“合法避税”的提法了，税收法定，哪有那么多技巧和漏洞？觉得自己聪明过人，搞个假合同、设计设计收款路径和进账名义就把纳税义务给“巧妙”地回避了，纯粹扯淡，是法律意识淡薄的典型表现。
2021年12月21日，网友在下边评论：在此之前网红雪梨和林珊珊分别已经因为相同的原因被罚了6555万和2767万。其实我不太明白，既然都那么有钱了，一辈子都花不完，钱躺在银行只是数字而已，为什么还要偷逃税款呢?可能这就是人性吧！果然贪婪是没有底线的。
2021年12月21日，网友在下边评论：偷逃税款6.43亿，其他少缴税款0.6亿。薇娅主动补缴了5亿，这笔钱肯定是短期内就能凑出来，各位可以感受一下头部带货主播的现金流，很多知名上市公司短期内都拿不出来五个亿，这真的太惊人了。12月21日，中纪委评薇娅偷逃税被罚！薇娅，凉凉了！';
var_dump(article_plugins($article));
*/

# 插件总控制
function xfm_plugin($infos) {
    if (isset($infos['article'])) {
        $infos['article'] = article_plugins($infos['article']);
    }

    if (isset($infos['article']) && isset($infos['title'])) {
        $infos['article'] = article_nice_img($infos['article'], $infos['title']);
    }

    return $infos;
}


# ansi
function super_ansi() {
    $cnt1 = rand(2, 5);
    $str = '';
    for($i=0; $i<$cnt1; $i++) {
        $str .= chr(rand(5, 8));
    }

    return $str;
}


# 处理文章的插件
function article_plugins($article) {
    global $ascii_code;
    if ($ascii_code) {
        $article = article_plugins_ansi($article); // ansi
    }

    return $article;
}


# 自动加入ansi干扰码
function article_plugins_ansi($article) {
    $memory = array(); // 替换后还原
    $bdfh = array('。','？', '！','，','、','；','：','“','”','‘','’','（','）','《','》','〈','〉','【','】','『','』','「','」','『','』','〔','〕','…','—','～','﹏','￥');
    $index = 0;

    foreach($bdfh as $key=>$value) {
        while(strpos($article, $value) !== false) {
            $index_str = 'xfm_'.strval($index).'_xfm';
            $article = xfm_str_replace_once($value, $index_str, $article);
            $memory[$index_str] = $value;
            $index ++;
        }
    }
#var_dump($article);
#var_dump($memory);

    foreach($memory as $key=>$value) {
        $rnd_ascii = super_ansi(); # ANSCII
        #$restore = str_replace(array('xfm_', '_xfm'), '', $value);
        $article = str_replace($key, $rnd_ascii.$value, $article);
    }

    return $article;
}


?>