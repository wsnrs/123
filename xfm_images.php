<?php
require_once ('xfm_config.php');
#echo get_images('百度伪原创工具');


//var_dump(get_local_images());

// 本地图片库 config/images/ 里面的 jpg 和 png 图片
function get_local_images() {
	if (isset($GLOBALS["config_dir"])) {
		$file_dir = $GLOBALS["config_dir"].DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;
	}
	else {
		$file_dir = 'config'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;
	}

	if (file_exists($file_dir)) {
		$dh  = opendir($file_dir);
		$tmp_arr = [];
		while (false !== ($filename = readdir($dh))) {
			if($filename !=".." && $filename !=".") {
				/*if(is_dir($dir."/".$filename)){
					$tmp_arr[$filename] = myScanDir($dir."/".$filename);
				}else{
					$tmp_arr[] = $filename;
				}*/
				if (strpos($filename, '.jpg') || strpos($filename, '.png')) {
					$tmp_arr[] = $file_dir.$filename;
				}
			}
	
		}
		closedir($dh);
	
		return $tmp_arr;
	}
	else {
		return array('config'.DIRECTORY_SEPARATOR.'thumb_2.jpg');
	}
}


function get_ai_images($text) {
	global $shuju_dir;
	global $image_api;

	if ($image_api === '') {
		return '';
	}

	$out_file = $shuju_dir.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.md5($text).'.png';
	if (file_exists($out_file)) {
		return $out_file;
	}

	$xfm_image_api = $image_api;
	$xfm_image_api .= urlencode($text);
	$xfm_image_api .= '.jpg';

	//if (!is_dir($shuju_dir.DIRECTORY_SEPARATOR.'images'))
		//mkdir($shuju_dir.DIRECTORY_SEPARATOR.'images', 0777);

	//$image_file = file_get_contents($xfm_image_api);
	//file_put_contents($out_file, $image_file);
	//file_put_contents($shuju_dir.DIRECTORY_SEPARATOR.'image_log.txt', $xfm_image_api.PHP_EOL, LOCK_EX | FILE_APPEND);
	return $xfm_image_api;
}



?>