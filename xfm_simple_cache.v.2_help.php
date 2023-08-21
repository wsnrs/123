<?php

// 升级版与老版本区别，以及如何判断新版本和老版本，如何兼容
// 新版本文件名改为 xxxxxxxx.txt.gz
// 通过判断是否存在 xxxxxxxx.txt.gz 来区分新老版本
// 老版本有两个文件 xxxxxxxx.txt 和 xxxxxxxx.txt.kw，分别代表文章和关键词

define('XFM_CACHE_SEPARATOR', '#$%$$%@');


// 全局变量
$g_cache_data = array('kw'=>'', 'html'=>'');


// 文章数据写入压缩厍
function xfm_put_gz_cache($keyword, $content) {
	$data = $keyword.XFM_CACHE_SEPARATOR.$content;
	$gz_filename = get_gz_file_name($keyword);
	//var_dump($keyword);
	//var_dump($gz_filename);
	return file_put_contents_zip($gz_filename, $data);
}

// 读取压缩文章数据
function xfm_get_gz_cache($filename) {
	$data = file_get_contents_zip($filename);
	$info = explode(XFM_CACHE_SEPARATOR, $data);
	return $info;
}


// 写入压缩数据
function file_put_contents_zip($filename, $data) {
	//var_dump($filename);
	$gzdata = gzcompress($data);
	return file_put_contents($filename, $gzdata);
}





// 读取压缩数据
function file_get_contents_zip($filename) {
	$gzdata = file_get_contents($filename);
	$data = gzuncompress($gzdata);
	//$filesize = filesize($filename);
	//$cache_date = date('Y-m-d h:i:s',filemtime($cache_file));
	return $data;// . '<!-- ' . $cache_date . ' xx ' . $filesize . ' -->';
}

function get_gz_file_name($keyword) {
	$cache_file = get_keyword_path($keyword);
	$gz_filename = $cache_file.'.gz';
	return $gz_filename;
}

// 判断是否新版本
function gz_file_exists($keyword) {
	$gz_filename = get_gz_file_name($keyword);
	//var_dump($gz_filename);
	if (file_exists($gz_filename)) {
		return true;
	}
	return false;
}


?>