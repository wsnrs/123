<?php

require_once(__DIR__.DIRECTORY_SEPARATOR.'word_base.php');


/*
var_dump(read_feihua());
*/



function get_url_file() {
    return 'knowledge'.DIRECTORY_SEPARATOR.'url_lib.txt';
}

# 读取词典数据库
function read_url() {
    $file = get_url_file();
    $arr = xfm_txt_to_array($file);
    return $arr;
}

?>