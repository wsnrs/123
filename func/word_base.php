<?php
/* 中文字符通用函数
*/
define('UNKNOWN_WORD_FILE', 'unknow_char.txt'); // 未知符号记录文件

// 判断是否数字或者中文数字
function is_numeric_zh($word) {
	return is_numeric($word);
}


function merge_spaces($string)
{
    return preg_replace ("/\s(?=\s)/","\\1", $string);
}


function newline2br($contnets) {
    $contnets = str_replace("\n", "<br>".PHP_EOL, $contnets);
//    $contnets = str_replace('><br><', '><', $contnets);
    $contnets = str_replace('<p><br>', '<p>', $contnets);
    return $contnets;
}



function save_unknow_char($value) {
	file_put_contents(UNKNOWN_WORD_FILE, $value, FILE_APPEND);
}

// 判断是否句子结尾
function theEndOfSentence($mb_char) {
	//中文标点
	$char = "。！？";

	if(preg_match('/['.$char.']/u' ,$mb_char)) {
		return true;
	}

	return false;
}


if ( ! function_exists('mb_str_split')) {
  function mb_str_split($str,$split_length=1,$charset="UTF-8"){
    if(func_num_args()==1){
      return preg_split('/(?<!^)(?!$)/u', $str);
    }
    if($split_length<1)return false;
    $len = mb_strlen($str, $charset);
    $arr = array();
    for($i=0;$i<$len;$i+=$split_length){
      $s = mb_substr($str, $i, $split_length, $charset);
      $arr[] = $s;
    }
    return $arr;
  }
}



function isChinesePunctuation($str) {

//$cp_arr = array('，', '。');
  //中文标点
$char = "，。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";



  if(preg_match('/['.$char.']/u' ,$str)) {
    return true;
  }

/*
$pattern = array(
    '/['.$char.']/u', //中文标点符号
);

$str = preg_replace($pattern, ' ', $str);

    $arrChinesePunctuation = array(
      '，', '。', '？', '！', '；', '：', '、'
      );

    foreach ($arrChinesePunctuation as $key => $value) {
        if ($str === $value) {
          return true;
        }
    }
*/
    return false;
}



function isEnglishPunctuation($str) {

//$cp_arr = array('，', '。');
  //中文标点
$char = ".,-?'()[]{}<>:;!\"";

  //if(preg_match('/['.$char.']/u' ,$str)) {
	if (preg_match("/[[:punct:]\s]/", $str)) {
		//echo 'xxxxxxxxxxxxxxxxxx';
		return true;
	}

/*
$pattern = array(
    '/['.$char.']/u', //中文标点符号
);

$str = preg_replace($pattern, ' ', $str);

    $arrChinesePunctuation = array(
      '，', '。', '？', '！', '；', '：', '、'
      );

    foreach ($arrChinesePunctuation as $key => $value) {
        if ($str === $value) {
          return true;
        }
    }
*/
    return false;
}

function isEnglish($str) {
  if(preg_match("/^[a-zA-Z]+$/",$str)){
    return true;
  }
  return false;
}

function IsAllChinese($str)
{
    $len = preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$str);
    if ($len)
    {
        return true;
    }
    return false;
}

// 删除bom
function remove_bom_str($bom_content) {
    return str_replace("\xef\xbb\xbf", '', $bom_content);
}


// 只替换一次
if (! function_exists('xfm_str_replace_once')) {
  function xfm_str_replace_once($search, $replace, $subject) {
      $firstChar = strpos($subject, $search);
      if($firstChar !== false) {
          $beforeStr = substr($subject,0,$firstChar);
          $afterStr = substr($subject, $firstChar + strlen($search));
          return $beforeStr.$replace.$afterStr;
      } else {
          return $subject;
      }
  }
}

// 重置换行符
function api_fix_newline($data) {
    $data = str_replace("\r", "\n", $data);
    while(strpos($data, "\n\n") !== false) {
        $data = str_replace("\n\n", "\n", $data);
    }
    //$data = str_replace("\n", , $data);

    return $data;
}

// 阿利伯转中文
function ToChinaseNum( $num)

{

$char = array( "零", "一", "二", "三", "四", "五", "六", "七", "八", "九");

$dw = array( "", "十", "百", "千", "万", "亿", "兆");

$retval = "";

$proZero = false;

for( $i = 0; $i < strlen( $num); $i++)

{

if( $i > 0) $temp = (int)(( $num % pow ( 10, $i+1)) / pow ( 10, $i));

else $temp = (int)( $num % pow ( 10, 1));

if( $proZero == true && $temp == 0) continue;

if( $temp == 0) $proZero = true;

else $proZero = false;

if( $proZero)

{

if( $retval == "") continue;

$retval = $char[$temp].$retval;

}

else $retval = $char[$temp].$dw[$i].$retval;

}

if( $retval == "一十") $retval = "十";

return $retval;

}
?>