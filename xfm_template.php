<?php
// xfm_template 用来处理模板



require_once('xfm_config.php');
require_once('error_log.php');

//echo get_template('article_template.html');

function get_template($template_filename) {
    global $template_dir;
    $template_path = $template_dir.DIRECTORY_SEPARATOR.$template_filename;
    if (file_exists($template_path)) {
        return file_get_contents($template_path);
    }
    else {
        log_error_info('file not exists.'.$template_path);
        return false;
    }
}



?>