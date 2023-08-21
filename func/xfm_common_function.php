<?php


# 字符串只替换一次
function xfm_news_str_replace_once($search, $replace, $subject) {
    $firstChar = strpos($subject, $search);
    if($firstChar !== false) {
        $beforeStr = substr($subject,0,$firstChar);
        $afterStr = substr($subject, $firstChar + strlen($search));
        return $beforeStr.$replace.$afterStr;
    } else {
        return $subject;
    }
}


// 从TXT文件读取数据
function txt2array($file_name) {

    if (! file_exists($file_name)) {
        return '';
    }

    $data = file_get_contents($file_name);
    $data = remove_bom_str($data);
    $data = str_replace("\r", "\n", $data);
    $data = str_replace("\n\n", "\n", $data);
    $data = str_replace("\n\n", "\n", $data);
    $data = str_replace("\n", PHP_EOL, $data);
    $arr = explode(PHP_EOL, $data);
    $last = array_pop($arr);
    if (strlen($last)>1) {
        $arr[] = $last;
    }
    $new_arr = array();
    foreach ($arr as $key => $value) {
        $new_arr[$key]=trim($value, '->');
    }

    return $new_arr;
}



?>