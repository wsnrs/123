<?php

$type_white = array('buyu', 'majiang', 'liao', 'lunwen','rengongzhineng','seo');
$type = isset($_GET['type'])?$_GET['type']:'';
if (! in_array($type, $type_white)) {
    echo '不支持类型';
    exit;
}

?>