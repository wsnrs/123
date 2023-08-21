<?php

// 用来重置整个网站，包括：
// 1、清空 article_cache 目录
// 2、清空 data 目录
// 3、清空 images 目录
require_once('xfm_config.php');
require_once ("3rd".DIRECTORY_SEPARATOR.'delete_dir.php');


empty_article_cache();

header('location: autorun.php');
# 清空 article_cache 目录
function empty_article_cache() {
    global $shuju_dir;
    $folder_name = $shuju_dir;
    deleteContent($folder_name);
}