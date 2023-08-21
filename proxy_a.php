<?php
require_once('proxy_curl_get.php');

$s = isset($_GET['wd'])?$_GET['wd']:'';
$pn = isset($_GET['pn'])?$_GET['pn']:'1';
if ($s !== '') {
    echo nice_baidu_get($s, $pn);
}
else {
    //echo 'what?';
    //var_dump($_GET);
}


?>