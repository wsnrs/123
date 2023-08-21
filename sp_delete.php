<?php

# 文章缓存模块
# 如果文章缓存存在，则读取缓存
require_once 'xfm_simple_cache.v.2.php';// 缓存
require_once 'xfm_debug.php';           // 调试信息
require_once "3rd".DIRECTORY_SEPARATOR."runtime_logger.php";      // 运行日志


if (!isset($short_url)) {
    $short_url = isset($_GET['title'])?$_GET['title']:'';
    if (delete_keyword_from_list($short_url)) {
        echo '关键词列表已清除'.$short_url;
    }
    else {
        echo '关键词列表里不存在'.$short_url;
    }
    //$short_url = str_replace('.html', '', $short_url);
}

echo '<hr>';
// 文章数据
$cache_file = get_keyword_path_v2($short_url);
$cache_gz_file = $cache_file.'.gz';
if (file_exists($cache_gz_file)) {
    var_dump($cache_gz_file);
    echo '删除缓存文件';
    echo '<hr>';
    file_put_contents($cache_kw_file, 'goodboy');
    unlink($cache_gz_file);
}
else {
    echo '缓存文件不存在 ' . $cache_gz_file;
}

// 关键词数据
$cache_kw_file = $cache_file.'.kw';
if (file_exists($cache_kw_file)) {
    //var_dump($cache_kw_file);
    //echo '<hr>';
    
    file_put_contents($cache_kw_file, ' 信息不存在');
    //unlink($cache_kw_file);
}

echo ' 该信息不存在';


?>