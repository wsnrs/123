<?php



function array_sort_by_len($arr) {
	$new_arr = array();
	foreach ($arr as $key => $value) {
        // 字数小的词不参与替换，不然影响太多
        $len = strlen($value);
        if ($len > 3) {
            $new_arr[$len*100+$key]=$value;
        }
	}

    // 重新排序数组
    krsort($new_arr);
    $ret_arr = array();
    foreach ($new_arr as $key => $value) {
        $ret_arr[] = $value;
    }

    return $ret_arr;
}



?>