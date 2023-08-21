<?php
require_once('func'.DIRECTORY_SEPARATOR.'xfm_txt_func.php');
require_once('xfm_config.php');

define('ASTR_SEP', '``');

//$_SERVER['REQUEST_URI'] = 'ksksksks/bbb';
//var_dump(get_404_keyword());

function get_404_keyword() {
    global $shuju_dir;
    $map_404_filename = $shuju_dir.DIRECTORY_SEPARATOR.'404_info.txt';
    $uri = $_SERVER['REQUEST_URI'];
    $map_404 = get_404_map();
    if ($map_404) {
        foreach ($map_404 as $k=>$v) {
            if (strpos($v, $uri) !== false) {
                return get_404_keyword_help($v);
            }
        }
    }

    $rand_keyword = get_404_rand_keyword();
    if ($rand_keyword) {
        $astr = $rand_keyword.ASTR_SEP.$uri;
        file_put_contents($map_404_filename, $astr.PHP_EOL, FILE_APPEND);
        return $rand_keyword;
    }
}


function get_404_rand_keyword() {
    global $config_dir;
    $keyword_filename = $config_dir.DIRECTORY_SEPARATOR.'spider.txt';
    $kw_arr = xfm_txt_to_array($keyword_filename);
    if (count($kw_arr)>0) {
        shuffle($kw_arr);
        return $kw_arr[0];
    }
    return false;
}

function get_404_keyword_help($str) {
    $aaa = explode('``', $str);
    return $aaa[0];
}

function get_404_map() {
    global $shuju_dir;
    $map_404_filename = $shuju_dir.DIRECTORY_SEPARATOR.'404_info.txt';
    $map_404 = xfm_txt_to_array($map_404_filename);
    if (count($map_404)>0) {
        return $map_404;
    }
    return false;
}


?>