<?php
require_once('xfm_str_func.php');

function text2html($article) {
    $article = fix_newline_PHP_EOL($article);
    $arr = explode(PHP_EOL, $article);
    $html = '';
    foreach($arr as $key=>$value) {
        if (strlen($value)>1) {
            $html.='<p>'.$value.'</p>'.PHP_EOL;
        }
    }
    return $html;
}

?>