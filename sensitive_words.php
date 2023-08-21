<?php
require_once('xfm_config.php');



if (! isset($GLOBALS["sensitive_words_file"])) {
    $sensitive_words_file = 'sensitive_word_short.txt';
}

//filtttt();
//var_dump(get_sensitive_words());

//$article = '床前明月光，疑是地上霜。';
// echo aritcle_filter($article);

/*
function filtttt() {
    $word_arr = get_sensitive_words();
    foreach ($word_arr as $key=>$value) {
        if (strlen($value) > 19) {
            @file_put_contents('sensitive_word_long.txt', $value."\n", FILE_APPEND | LOCK_EX);
        }
        else {
            @file_put_contents('sensitive_word_short.txt', $value."\n", FILE_APPEND | LOCK_EX);
        }
    }

    //return $article;
}*/


// 无效函数，为了兼容
function aritcle_filter($article) {
    return $article;
}


// 过滤文章的敏感词
function aritcle_sensitive_filter($article) {
    global $shuju_dir;
    $word_arr = get_sensitive_words();
    foreach ($word_arr as $key=>$value) {
        //if (strlen($value[0])< 4) {
        //    continue;
        //}
        if (strpos($article, $value[0]) !== false) {
            //echo '<!-- '. $value . '-->';
            @file_put_contents($shuju_dir.DIRECTORY_SEPARATOR.'sensitive_word.log', implode('`', $value).PHP_EOL, FILE_APPEND | LOCK_EX);
            //$cache_modify = true;
        }
        $article = str_replace($value[0], $value[1], $article);
    }

    return $article;
}


// 替换成什么词
function get_replace_word() {
    $faa = array('机器人不能说', '人工智能不能说','阿发狗','机器人','人工智能','小冰不能说');
    shuffle($faa);

    return $faa[0];
}


function get_sensitive_words() {
    global $sensitive_words_file;
    $data = file_get_contents($sensitive_words_file);
    $data = sensitive_words_fix_newline($data);
    $data_arr = explode(PHP_EOL, $data);
    $ret_arr = array();
    foreach ($data_arr as $key=>$value) {
        if (strlen($value)>1) {
            $ret_arr[$key] = explode('`', $value);
        }
    }
    return $ret_arr;
}


function sensitive_words_fix_newline($data) {
    $data = str_replace("\r", "\n", $data);
    while(strpos($data, "\n\n") !== false) {
        $data = str_replace("\n\n", "\n", $data);
    }
    $data = str_replace("\n", PHP_EOL, $data);
 
    return $data;
}

?>