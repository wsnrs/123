<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'word_base.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'xfm_txt_func.php');


/*
var_dump(read_yanyu());
*/



function get_yanyu_file() {
    return 'dict'.DIRECTORY_SEPARATOR.'yanyu.txt';
}

# 读取词典数据库
function read_yanyu() {
    $file = get_yanyu_file();
    $arr = xfm_txt_to_array($file);
    return $arr;
}


?>