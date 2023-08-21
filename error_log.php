<?php
require_once('xfm_config.php');


function log_error_info($error_info) {
    global $shuju_dir;
    global $error_log_filename;
    $error_file = $shuju_dir.DIRECTORY_SEPARATOR.$error_log_filename;
    $date = date('Y-m-d H:i:s');
    file_put_contents($error_file, $date.' - '. $error_info.PHP_EOL, FILE_APPEND | LOCK_EX);
}


?>