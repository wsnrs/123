<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'word_base.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'xfm_txt_func.php');


/*
var_dump(read_chengyu());
*/



function get_chengyu_file() {
    return 'dict'.DIRECTORY_SEPARATOR.'chengyu.txt';
}

# 读取词典数据库
function read_chengyu() {
    $file = get_chengyu_file();
    $arr = xfm_txt_to_array($file);
    return $arr;
}


?>