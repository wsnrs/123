<?php
# 文章缓存模块
# 如果文章缓存存在，则读取缓存
require_once 'xfm_simple_cache.v.2.php';// 缓存
require_once 'xfm_debug.php';           // 调试信息
require_once "3rd".DIRECTORY_SEPARATOR."runtime_logger.php";      // 运行日志
$cache_fuck = false;

if (!isset($short_url)) {
    $short_url = isset($_GET['title'])?$_GET['title']:'';
}

$xfm_cache_kw = $short_url; // 关键词+页码
$is_old_title = false;

$s = get_real_keyword($short_url); // 关键词URL加密
if (my_filter_fuck($s)) {
    //header('location:user_push.php');
    echo 'my_filter_fack';
    exit;
}
show_debug_info2('真实关键词', $s);    // 说明这个关键词不是系统获取的


// 如果是非正常URL，则显示404并退出
if ($s === '') {
    rt_log_error('bad guy short_url: '.$short_url);
    //header('location: user_push.php');
    echo '$s===';
    //echo '404.';
    exit;
}



# 删除缓存操作
if (isset($_GET['opt']) && $_GET['opt']==='del') {
    echo '<!-- ';
    var_dump(xfm_delete_cache_v2($xfm_cache_kw));
    @file_put_contents('xfm_cache_delete_log.txt', $xfm_cache_kw.PHP_EOL, FILE_APPEND);
    echo ' -->';
    echo '缓存已删除';
    exit;
}


// 获取关键词对应的缓存地址
#var_dump($xfm_cache_kw);
$xfm_is_cache = false;
$xfm_cache = xfm_get_cache_v2($xfm_cache_kw);

if ($xfm_cache !== '') {
    #show_debug_info_textarea('缓存：', $xfm_cache);
    // 缓存文件可能为空
    //if (strlen($xfm_cache) < $template_len+3000) {
    //    xfm_delete_cache($xfm_cache_kw);
    //}
    //else {
    show_html_comment('cache length:'.strlen($xfm_cache));
    $xfm_is_cache = true;

    require_once('sensitive_words.php');
    $xfm_cache = aritcle_filter($xfm_cache);
    /*if (strpos($xfm_cache, '此文章处于编辑状态') !== false) {
        $is_old_title = true;
        $xfm_is_cache = false;
    }
    else if (strpos($xfm_cache, '文章长度：2太短了') !== false) {
        $is_old_title = true;
        $xfm_is_cache = false;
    }*/
    if (false) {
        
    }
    else {
        echo $xfm_cache;
    }
    //}
}
else {
    show_debug_info('没有缓存 title：'.$short_url);
}


function my_filter_fuck($article) {
    global $cache_fuck;

    $word_arr = array('菩萨', '肉莲', '明妃', '密宗', '法器', '佛学', '割莲', '活取','政务',
                    '藏传', '人皮', '警察', '特警', '金刚杵','钟娜', '乌坎', '陆丰',
                    '公安', '制毒', '政府', '书记', '党委', '汕尾', '身份证', '市委',
                    '副主席', '主席', '习近平', '事件', '揭阳','强㢨','未成年',
                    '身份号', '分尸', '卖婬','普宁','新疆','西藏','布达拉宫','藏南',
                    '昆仑山','援藏','赵立坚','冲突','烈士','看守所','米俊海','佛法',
                    '佛教','印尼', '迷魂药','微信号','微信小号',
                    '收购微信','微信回收','回收微信','微信号','微信账号','vx号','账号回收','婴儿汤',
                    '自杀','四海帮','六合彩','周小川','刘明康','尚福林','孔丹','回民吃猪肉',
                    '习近平','毛民进党','1989六四','六四','李红智','梁光烈','坤迈','高嘉',
                    '国管局','天安门','洪志','法轮','胡新宇','何加棟','王子杰','庆林',
                    '刘云山','喇嘛','六四','罂粟','海洛因','惨案','菠菜','分尸','自尽','武警','解放军','军警','突击队','当兵','交警',
                    '雪豹','消防','将军','女兵','紫圣','真相'
                    );
    foreach ($word_arr as $key=>$value) {
        if (strpos($article, $value) !== false) {
            echo '<!-- '. $value . '-->';
            //@file_put_contents('sensitive_word.log', $value.PHP_EOL, FILE_APPEND | LOCK_EX);
            $cache_fuck = true;
        }
    }

    return $cache_fuck;
}

?>