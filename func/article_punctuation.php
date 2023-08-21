<?php
/* 用来修复标点符号
*/

require_once('word_base.php');

/* 参数 $article 是文章 
返回：array('new_arr', 'fix_arr_key')
new_arr 时候修复后的字数组，fix_arr_key 是被修改的符号的key
*/





function fix_punctuaction($article) {

	// 修复句尾的标点符号，例如两个句号。。
	$article = api_fix_newline($article);
	$article = remove_duplicate_punctuaction($article);

	$article_word_arr = mb_str_split($article);
	$ret_str = '';
	$changes = array();
	$ret_word_arr = array();
	foreach ($article_word_arr as $key => $value) {
		# code...
		if (isEnglishPunctuation($value)) { // 汉字后面的标点符号要修复成汉字符号
			#var_dump($article_word_arr[$key-1] . $value);
			#var_dump($value);
			// 标点符号之间的空格
			if ($value === ' ' && IsAllChinese($article_word_arr[$key-1])) {
				#echo 'xxxx';
				$ret_str .= '';
				#$ret_word_arr[] = $value;
			}
			else if ($value === ' ' && isChinesePunctuation($article_word_arr[$key-1])) {
				#echo 'xxxx';
				$ret_str .= '';
				$ret_word_arr[] = $value;
			}
			else if ($value === "\n") {
				$ret_str .= $value;
				$ret_word_arr[] = $value;
			}
			else if (isset($article_word_arr[$key-1]) && IsAllChinese($article_word_arr[$key-1])) {
				#var_dump($article_word_arr[$key-1]);
				$new_value = fix_PunctuationEn2Zh($value);
				$ret_str .= $new_value;
				$ret_word_arr[] = $new_value;
				$changes[] = $key;
			}
			else if (isset($article_word_arr[$key-1]) && $article_word_arr[$key-1] === '”') {
				// 处理这种情况 "”,“"
				#var_dump($article_word_arr[$key-1]);
				$new_value = fix_PunctuationEn2Zh($value);
				$ret_str .= $new_value;
				$ret_word_arr[] = $new_value;
				$changes[] = $key;
			}
			else if (isset($article_word_arr[$key-1]) && isChinesePunctuation($article_word_arr[$key-1])) {
				// 处理这种情况 "：,"
				#echo 'xxbbxx';
				#var_dump($article_word_arr[$key-1]);
				$ret_str .= ''; // 删除处理
				$ret_word_arr[] = $value;
			}
			else {
				$ret_str .= $value;
				$ret_word_arr[] = $value;
			}
		}
		#else if ($value === ' ') { // 汉字符号
		#	if (isset($article_word_arr[$key-1]) && ($article_word_arr[$key-1] === ' ') ) {
		#	}
		#}
		else {
			$ret_str .= $value;
			$ret_word_arr[] = $value;
		}
	}

	#$ret_str = trim($ret_str);
	return array($ret_str, $ret_word_arr, $changes);
}


// 删除重复的标点符号
function remove_duplicate_punctuaction($article) {
	$fvck_val = 'xxx`xxxxxxbxxxxx';
	$new_article = '';
	$para_arr = explode("\n", $article);
	foreach ($para_arr as $key => $value) {
		$tmp_value = $value.$fvck_val;
		#echo $tmp_value."\n";

		$tmp_value = str_replace('。。'.$fvck_val, '。', $tmp_value);
		$tmp_value = str_replace('，。'.$fvck_val, '。', $tmp_value);
		$tmp_value = str_replace('、。'.$fvck_val, '。', $tmp_value);
		#echo '。。'.$fvck_val."\n";
		#echo $tmp_value."\n";
		$tmp_value = str_replace($fvck_val, '', $tmp_value);
		#echo $tmp_value."\n";
		$new_article .= $tmp_value;
		$new_article .= "\n";
	}

	return $new_article;
}


// 英文标点符号转中文标点符号
function fix_PunctuationEn2Zh($value) {
	$array_en = array(':',  ',',  '?',  ';', '!', ' ');
	$array_zh = array('：', '，', '？', '；', '！', '，');

	$ret_str = str_replace($array_en, $array_zh, $value);
	return $ret_str;
}


function fix_punctuaction_old($article_word_arr) {

	$ret_str = '';
	$ret_word_arr = array();
	foreach ($article_word_arr as $key => $value) {
		# code...
		if ($value === "\n") {
			$ret_str .= "\n";
			$ret_word_arr[] = "\n";
			/*
			if (! isChinesePunctuation($article_word_arr[$key-1])) {
				//echo '，';
				$ret_str .= ' ';
			}
			else {

			}*/
		}
		else if (IsAllChinese($value)) { // 汉字
			//echo $value;
			$ret_str .= $value;
			$ret_word_arr[] = $value;
		}
		else if (isChinesePunctuation($value)) { // 汉字符号
			//echo $value;
			$ret_str .= $value;
			$ret_word_arr[] = $value;
		}
		else if (isEnglishPunctuation($value)) { // 汉字后面的标点符号要修复成汉字符号
			#var_dump($value);
			if (isset($article_word_arr[$key-1]) && IsAllChinese($article_word_arr[$key-1])) {
				#var_dump($article_word_arr[$key-1]);
				$new_value = fix_PunctuationEn2Zh($value);
				$ret_str .= $new_value;
				$ret_word_arr[] = $new_value;
			}
			else {
				$ret_str .= $value;
				$ret_word_arr[] = $value;
			}
		}
		else if (isEnglish($value)) { // 英文字符
			if (isset($article_word_arr[$key+1]) && $article_word_arr[$key+1]===' ') {
				//echo $value;
				//echo ' ';
				#$new_value = $value . ' ';
				#$ret_str .= $new_value;
				$ret_str .= $value;
				$ret_word_arr[] = $value;
				#$ret_word_arr[] = $new_value;
			}
			else {
				//echo $value;
				$ret_str .= $value;
				$ret_word_arr[] = $value;
			}
		}
		else if (is_numeric($value)) {
			//echo $value;
			$ret_str .= $value;
			$ret_word_arr[] = $value;
		}
		else {
			save_unknow_char($value);
		}
	}

	#$ret_str = trim($ret_str);
	return array($ret_str, $ret_word_arr);
}




?>