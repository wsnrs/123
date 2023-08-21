<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'word_base.php');


/*
var_dump(read_feihua());
*/



function get_feihua_file() {
    return 'dict'.DIRECTORY_SEPARATOR.'feihua.txt';
}

# 读取词典数据库
function read_feihua() {
    $file = get_feihua_file();
    $arr = xfm_txt_to_array($file);
    return $arr;
}


?>