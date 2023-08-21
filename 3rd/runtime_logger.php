<?php

define('RUNTIMELOG', 'sn_log.txt');
define('RUNTIMELOG_DATA', 'sn_log_data.txt');
define('RUNTIMELOG_ERROR', 'data'.DIRECTORY_SEPARATOR.'sn_log_error.txt');

function rt_log($info) {
    $date = date('Y-m-d H:i:s');
    $log_str = $date . ' - ' . $info;
    @file_put_contents(RUNTIMELOG, $log_str.PHP_EOL, FILE_APPEND);
}

function rt_log_data($info) {
    $date = date('Y-m-d H:i:s');
    $log_str = $date . ' - ' . $info;
    @file_put_contents(RUNTIMELOG_DATA, $log_str.PHP_EOL, FILE_APPEND);
}


function rt_log_error($info) {
    $date = date('Y-m-d H:i:s');
    $log_str = $date . ' - ' . $info;
    @file_put_contents(RUNTIMELOG_ERROR, $log_str.PHP_EOL, FILE_APPEND);
}


?>