<?php


function get_utf8_data($data) {
    $encode = mb_detect_encoding($data, array('ASCII', 'UTF-8', 'GB2312', 'GBK','BIG5'));
    if ($encode !== 'UTF-8') {
        //show_info_gb2312('文件非 UTF-8，正在转成UTF-8');
        $utf8_data = iconv($encode, 'UTF-8//IGNORE', $data);
        return $utf8_data;
    }

    return $data;
}



?>