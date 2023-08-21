<?php



# 控制是否显示调试信息
function is_global_debug() {
    global $is_debug;
    return $is_debug;
}


# 调试信息输出
function show_debug_info($str) {
    if (! is_global_debug()) {return;}
    echo '<p>'.$str.'</p>'.PHP_EOL;
}


# 调试信息输出
function show_debug_info2($title, $str) {
    if (! is_global_debug()) {return;}
    echo '<h3>'.$title.'</h3>'.PHP_EOL;
    echo '<p>'.$str.'</p>'.PHP_EOL;
}


# 调试信息输出
function show_debug_info_textarea($title, $str) {
    if (! is_global_debug()) {return;}
    echo '<h3>'.$title.'</h3>'.PHP_EOL;
    echo '<textarea style="width:500px;height:200px;">'.$str.'</textarea>'.PHP_EOL;
}


# HTML注释信息
function show_html_comment($str) {
    if (! is_global_debug()) {return;}
    echo '<!--'.PHP_EOL;
    echo $str.PHP_EOL;
    echo '-->'.PHP_EOL;;
}

?>