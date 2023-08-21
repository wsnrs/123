<?php

#error_reporting(E_ALL);
#ini_set('display_errors', '1');
require_once('xfm_config.php');
require_once('3rd'.DIRECTORY_SEPARATOR.'short_url.php');
require_once('xfm_simple_cache.v.2_help.php');
require_once('func'.DIRECTORY_SEPARATOR.'xfm_txt_func.php');
define('XFM_CACHE_BASE', $shuju_dir.DIRECTORY_SEPARATOR.'article_cache');

//var_dump();
//exit;
//xfm_put_keyword_cache('神经网络');
//var_dump(get_real_keyword('00bjts9j'));



function my_update_dir($short_url) {
	$sub_dir = substr($short_url, 0, 2);
	$sur_dir_full = XFM_CACHE_BASE.DIRECTORY_SEPARATOR.$sub_dir.DIRECTORY_SEPARATOR;
	if (!file_exists($sur_dir_full)) {
		mkdir_r($sur_dir_full);
	}
	else {
		$dir = myScanDir_kw($sur_dir_full);
		foreach ($dir as $value) {
			// echo $value;
			$real_keyword = file_get_contents($value);
			xfm_put_keyword_cache($real_keyword); // 保存新格式
			unlink($value); // 删除，省得占空间
		}
	}
	
}


// 遍历当前目录所有老版本的关键词文件
// 2021-6-2 日升级
// 解决有的服务器禁止 scandir 问题
function myScanDir_kw($dir) {

	$dh  = opendir($dir);
    $new_arr = [];
    while (false !== ($filename = readdir($dh))) {
        if($filename !=".." && $filename !=".") {
			if (strpos($filename, '.txt.kw') !== false)
			{
				$new_arr[] = $dir.$filename;
			}
        }
    }
    closedir($dh);
    return $new_arr;

/*
    $file_arr = scandir($dir);
    $new_arr = [];
    foreach($file_arr as $item){
        if($item!=".." && $item !="."){
			if (strpos($item, '.txt.kw') !== false)
			{
				$new_arr[] = $dir.$item;
			}
        }
    }
    return $new_arr;*/
}


// 遍历当前目录所有老版本的关键词文件
function myScanDir_kw_old_20210602($dir) {
    $file_arr = scandir($dir);
    $new_arr = [];
    foreach($file_arr as $item){
        if($item!=".." && $item !="."){
			if (strpos($item, '.txt.kw') !== false)
			{
				$new_arr[] = $dir.$item;
			}
        }
    }
    return $new_arr;
}



// 通过短网址获取关键词
function get_real_keyword($short_url) {
	$xfm_keyword_file = get_keyword_list_paty_short_url($short_url);
	$keyword_list = xfm_txt_to_array($xfm_keyword_file);
	if ($keyword_list) {
		foreach ($keyword_list as $value) {
			if (strpos($value, $short_url) !== false) {
				$temp_arr = explode('`', $value);
				return $temp_arr[1];
			}
		}
	}

	// 能运行到这里，说明关键词没找到
	$cache_file = get_keyword_path_v2($short_url);
	$cache_gz_file = $cache_file.'.gz';
	$real_keyword = '';
	if (file_exists($cache_gz_file)) { // 压缩版
		$article_data = xfm_get_gz_cache($cache_gz_file);
		$real_keyword = $article_data[0];
	}
	else {
		$real_keyword_file = get_keyword_path_v2($short_url);
		$real_keyword_file = $real_keyword_file . '.kw';
		if (file_exists($real_keyword_file)) {
			$real_keyword = file_get_contents($real_keyword_file);
			xfm_put_keyword_cache($real_keyword); // 保存新格式
			unlink($real_keyword_file); // 删除，省得占空间
		}
	}

	return $real_keyword;
}

// 写入关键词缓存
function xfm_put_keyword_cache($keyword) {
	$xfm_keyword_file = get_keyword_list_path($keyword);
	$short_url = short_md5($keyword);
	if (short_url_exists($short_url)) {
		return false;
	}

	if (! file_exists($xfm_keyword_file)) { // 压缩版
		file_put_contents($xfm_keyword_file, $short_url.'`'.$keyword.PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	else {
		file_put_contents($xfm_keyword_file, $short_url.'`'.$keyword.PHP_EOL, FILE_APPEND | LOCK_EX);
	}

	return true;
}


// 防止重复写入，只能通过short_url来做唯一ID，keyword会有长尾词，不好唯一
function short_url_exists($short_url) {
	$xfm_keyword_file = get_keyword_list_paty_short_url($short_url);
	if (file_exists($xfm_keyword_file)) {
		$data = file_get_contents($xfm_keyword_file);
		if (strpos($data, $short_url.'`') !== false) {
			return true;
		}
	}

	return false;
}


// 通过短网址获取关键词的列表目录
function get_keyword_list_paty_short_url($short_url) {
	$sub_dir = substr($short_url, 0, 2);
	$sur_dir_full = XFM_CACHE_BASE.DIRECTORY_SEPARATOR.$sub_dir.DIRECTORY_SEPARATOR;
	if (!file_exists($sur_dir_full)) {
		mkdir_r($sur_dir_full);
	}
	return $sur_dir_full.'keyword_list.txt';
}


// 关键词列表文件
function get_keyword_list_path($keyword) {
	$dir = get_keyword_dir($keyword);
	if ($dir !== '') {
		return $dir.'keyword_list.txt';
	}

	return '';
}

#var_dump(get_real_keyword('h5hsj8jxu'));
/*
$_string = 'originate什么意思中文翻译';
$content = '李白的静夜思';
$_string = 'he7ckke4';
// 获取关键词对应的缓存地址
$xfm_cache = xfm_get_cache_v2($_string);
if ($xfm_cache !== '') {
	#xfm_delete_cache($_string);
	var_dump($xfm_cache);
}
else {
	var_dump(xfm_put_cache($_string, $content));
}
*/


// 关键词缓存是否存在
function xfm_get_keyword_exists($keyword) {
	$cache_file = get_keyword_path($keyword);
	$cache_file_kw = $cache_file.'.kw';
	#var_dump($cache_file_kw);
	if (file_exists($cache_file_kw)) {
		return true;
	}
	return false;
}


// 写入关键词缓存
function xfm_put_keyword_cache_old($keyword) {
	//return xfm_put_gz_cache($keyword, '');

	$cache_file = get_keyword_path($keyword);
	$cache_gz_file = $cache_file.'.gz';
	if (! file_exists($cache_gz_file)) { // 压缩版
		return xfm_put_gz_cache($keyword, '');
	}

	return true;
}



function xfm_put_cache($keyword, $content) {
	return xfm_put_gz_cache($keyword, $content);
	//$cache_file = get_keyword_path($keyword);
	//$cache_file_kw = $cache_file.'.kw';
	//file_put_contents($cache_file_kw, $keyword);
	//return file_put_contents($cache_file, $content);
}


// 删除缓存，用来强制更新
function xfm_delete_cache($keyword) {
	$cache_file = get_keyword_path($keyword);
	if (file_exists($cache_file)) {
		return unlink($cache_file);
	}

	return false;
}

// 删除缓存，用来强制更新
function xfm_delete_cache_v2($keyword) {
	$cache_file = get_keyword_path_v2($keyword);
	if (file_exists($cache_file)) {
		return unlink($cache_file);
	}

	return false;
}

// 通过短网址获取关键词
function get_real_keyword_old($short_url) {

	$cache_file = get_keyword_path_v2($short_url);
	$cache_gz_file = $cache_file.'.gz';
	if (file_exists($cache_gz_file)) { // 压缩版
		$article_data = xfm_get_gz_cache($cache_gz_file);
		return $article_data[0];
	}
	else {
		$real_keyword_file = get_keyword_path_v2($short_url);
		$real_keyword_file = $real_keyword_file . '.kw';
		if (file_exists($real_keyword_file)) {
			return file_get_contents($real_keyword_file);
		}
	}

	return '';
}


// 老版本的cache
function get_old_cache($short_url) {
	$cache_file = get_keyword_path_v2($short_url);
	if (file_exists($cache_file)) {
		show_debug_info($cache_file.' 存在');
		$data = file_get_contents($cache_file);
		$filesize = filesize($cache_file);
		$cache_date = date('Y-m-d h:i:s',filemtime($cache_file));
		return $data;// . '<!-- ' . $cache_date . ' xx ' . $filesize . ' -->';
	}
	else {
		show_debug_info($cache_file.' 不存在');
		return '';
	}
}


// 把老数据更新成新数据
function old_cache_to_gz_cache($short_url) {
	$old_contents = get_old_cache($short_url);
	$old_keyword = get_real_keyword($short_url);
	xfm_put_gz_cache($old_keyword, $old_contents);

	$old_cache_file = get_keyword_path_v2($short_url);
	//var_dump($old_cache_file);
	$old_kw_cache_file = $old_cache_file.'.kw';
	unlink($old_cache_file);
	unlink($old_kw_cache_file);
}


function xfm_get_cache_v2($short_url) {

	my_update_dir($short_url);
	if (ctype_alnum($short_url)) {

	}

	$old_cached = get_old_cache($short_url);
	if ($old_cached !== '') { // 老版本缓存，存在
		//return $old_cached.'<!-- old cache -->';
		old_cache_to_gz_cache($short_url);
	}

	$cache_file = get_keyword_path_v2($short_url);
	$cache_gz_file = $cache_file.'.gz';
	if (file_exists($cache_gz_file)) {
		show_debug_info($cache_gz_file.' 存在');
		$article_data = xfm_get_gz_cache($cache_gz_file);
		$filesize = filesize($cache_gz_file);
		$cache_date = date('Y-m-d h:i:s', filemtime($cache_gz_file));
		show_debug_info2('gz data', serialize($article_data));
		if ($article_data[1] !== '') {
			return $article_data[1]. '<!-- ' . $cache_date . ' xx ' . $filesize . ' -->';
		}
		else {
			return get_old_cache($short_url);
		}
	}
	else {
		show_debug_info($cache_gz_file.' 不存在');
	}

	return '';
}


function xfm_get_cache($keyword) {
	$cache_file = get_keyword_path($keyword);
	#var_dump($cache_file);
	if (file_exists($cache_file)) {
		$data = file_get_contents($cache_file);
		$filesize = filesize($cache_file);
		$cache_date = date('Y-m-d h:i:s',filemtime($cache_file));
		return $data . '<!-- ' . $cache_date . ' xx ' . $filesize . ' -->';
	}
	else {
		return '';
	}
}




// 过滤掉一些特殊符号
function string_filter($str) {
	return str_replace(array(PHP_EOL), array(','), $str);
}



// 关键词简称
function get_keyword_dir($keyword) {
	$aaa = short_md5($keyword);
	$sub_dir = substr($aaa, 0, 2);
	$sur_dir_full = XFM_CACHE_BASE.DIRECTORY_SEPARATOR.$sub_dir.DIRECTORY_SEPARATOR;
	if (!file_exists($sur_dir_full)) {
		mkdir_r($sur_dir_full);
	}
	return $sur_dir_full;
}

// 关键词简称
function get_keyword_dir_v2($short_url) {
	$aaa = $short_url;
	$sub_dir = substr($aaa, 0, 2);
	$sur_dir_full = XFM_CACHE_BASE.DIRECTORY_SEPARATOR.$sub_dir.DIRECTORY_SEPARATOR;
	if (!file_exists($sur_dir_full)) {
		mkdir_r($sur_dir_full);
	}
	return $sur_dir_full;
}

// 关键词的缓存路径
function get_keyword_path_v2($short_url) {
	$dir = get_keyword_dir_v2($short_url);
	if ($dir !== '') {
		return $dir.$short_url.'.txt';
	}

	return '';
}

// 关键词的缓存路径
function get_keyword_path($keyword) {
	$dir = get_keyword_dir($keyword);
	if ($dir !== '') {
		return $dir.short_md5($keyword).'.txt';
	}

	return '';
}


// 关键词的缓存是否存在
function get_keyword_cache($keyword) {
}


function mkdir_r($dirName, $rights=0777) {
	$dirName = str_replace("\\", "/", $dirName);
    $dirs = explode('/', $dirName);
    $dir='';
    foreach ($dirs as $part) {
        $dir.=$part.'/';
        if (!is_dir($dir) && strlen($dir)>0)
			if (!file_exists($dir)) {
				mkdir($dir, $rights);
			}
    }
}

?>