<?php
// 用来给缓存文件加链接，带蜘蛛
require_once('xfm_news.php');
require_once('xfm_common_function.php');


function xfm_new_href($article) {
    $old_href = get_string_between($article, '<div class="sidebar-content sidebar-post hero-aside-relate-article">', '</ul>');
    return str_replace($old_href,  xfm_get_new_href(), $article);
}


// 新的链接
function xfm_get_new_href() {
	$template = '<li>
<a href="{HREF}" title="{TEXT}">
<div class="sidebar-post-title">{TEXT2}</div>
<img src="https://wx1.sinaimg.cn/large/007zsNlWgy1gpnanlsyjwj300i00k08r.jpg" referrerpolicy="no-referrer">
</a>
</li>
';

	$new_str = '
	<h2></h2>
<ul>
';
	for($i=0; $i<5; $i++) {
		$new_str .= xfm_news_list($template);
	}

	return $new_str;
}

function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}



