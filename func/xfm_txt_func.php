<?php
# 2022-6-12 增加了保存函数

require_once(__DIR__.DIRECTORY_SEPARATOR.'word_base.php');
// 记录读取TXT次数
$xfm_txt_read_count = 0;

//$data = file_get_contents($file_name);
//$data = remove_bom_str($data);

// 把arr保存成txt
function xfm_array_to_txt($arr, $file_name) {
    $data =implode($arr, PHP_EOL);
    return file_put_contents($file_name, $data);
}

// 把txt读取成array
function xfm_txt_to_array($file_name) {
    global $xfm_txt_read_count;
    $xfm_txt_read_count += 1;

    if (! file_exists($file_name)) {
        return false;
    }

    $data = file_get_contents($file_name);
    $data = remove_bom_str($data);
    $data = xfm_newline_to_php_eol($data);
    $arr = explode(PHP_EOL, $data);
    return $arr;
    //$last = array_pop($arr);
    //if (strlen($last)>1) {
    //    $arr[] = $last;
    //}
    //$new_arr = array();
    //foreach ($arr as $key => $value) {
    //    $new_arr[$key]=trim($value, '->');
    //}
    //shuffle($new_arr);
    //return $new_arr;
}

// 统一换行符
function xfm_newline_to_php_eol($data) {
    $data = str_replace("\r", "\n", $data);
    while(strpos($data, "\n\n") !== false) {
        $data = str_replace("\n\n", "\n", $data);
    }
    $data = str_replace("\n", PHP_EOL, $data);
    $data = trim($data);
    return $data;
}


?>