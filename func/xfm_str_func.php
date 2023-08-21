<?php


// 两个字符串之间的字符串
if (! function_exists('string_between_two_string')) {
    function string_between_two_string($str, $starting_word, $ending_word)
    {
        $subtring_start = strpos($str, $starting_word);
        //Adding the starting index of the starting word to
        //its length would give its ending index
        $subtring_start += strlen($starting_word);
        //Length of our required sub string
        $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
        // Return the substring from the index substring_start of length size
        return substr($str, $subtring_start, $size);
    }
}



// 对换行符进行统一
if (! function_exists('fix_newline_PHP_EOL')) {
    function fix_newline_PHP_EOL($data) {
        $data = str_replace("\r", "\n", $data);
        while(strpos($data, "\n\n") !== false) {
            $data = str_replace("\n\n", "\n", $data);
        }
        $data = str_replace("\n", PHP_EOL, $data);

        return $data;
    }
}


// 只替换一次
if (! function_exists('xfm_str_replace_once')) {
    function xfm_str_replace_once($search, $replace, $subject) {
        $firstChar = strpos($subject, $search);
        if($firstChar !== false) {
            $beforeStr = substr($subject,0,$firstChar);
            $afterStr = substr($subject, $firstChar + strlen($search));
            return $beforeStr.$replace.$afterStr;
        } else {
            return $subject;
        }
    }
}


// 把str转array
function xfm_str_to_array($data) {
    $data = fix_newline_PHP_EOL($data);
    $arr = explode(PHP_EOL, $data);
    return $arr;
}



function mb_rtrimx($string, $charlist = null) {
    if (is_null($charlist)) {
        return rtrim($string);
    } else {
        $charlist = preg_quote($charlist, '/');
        return preg_replace("/([$charlist]+$)/us", '', $string);
    }
}

function mb_ltrimx($string, $charlist = null) {
    if (is_null($charlist)) {
        return ltrim($string);
    } else {
        $charlist = preg_quote($charlist, '/');
        return preg_replace("/(^[$charlist]+)/us", '', $string);
    }
}



if(!function_exists('mb_ltrim')) {
    function mb_ltrim($str, $char){
        if(empty($str)) return '';
        while (mb_substr($str, 0, 1) == $char){
            $str = mb_substr($str, 1);
        }
        return $str;
    }
}

if(!function_exists('mb_rtrim')) {
    function mb_rtrim($str, $char){
        if(empty($str)) return '';
        while (mb_substr($str, -1, 1) == $char){
            $str = mb_substr($str, 0, -1);
        }
        return $str;
    }
}

if(!function_exists('mb_trim')) {
    function mb_trim($str, $char){
        return mb_rtrim(mb_ltrim($str, $char), $char);
    }
}


?>