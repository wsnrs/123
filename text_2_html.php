<?php
require_once 'xfm_images.php';
require_once 'baidu_qi_ta_ren.php';

// 配置新，未来会移到 xfm_config.php
$num_rate = 3;	// 加编号的概率，2代表 1/2， 10代表 1/10
$h2_rate = 10; // 加 h1 的概率，2代表 1/2， 10代表 1/10

/*
$title_src = '百度伪原创';

$content_src = '2019年，百度开始对神经网络伪原创，计算能力、算法、模型进行研究，突破了神经网络伪原创的核心技术瓶颈。基于AI算法的伪原创程序百度： 2018年，小最佳答案： 伪原创最好是人工的方式，如果采用软件的话，难免被识破，而且，如果是真正想要做互联网的话，就必须扎扎实实的走好每一步。更多关于百度伪原创工具的问题>>。
最佳答案： 作为一个平时需要处理大量伪原创文章的seo人员来说，我想我还是用过比较多的伪原创工具的，比如5118,奶盘，火车头，百度等，都是比较好用的伪原创工具。更多关于百度伪原创工具的问题>>我也是伪原创，用的百度AI,收录还不错。3 个月前赞同2 评论0 收藏举报自己原创质量是最高的，伪原创工具比较好点的我之前用过5118 现在很少用3 。
o(?amp;quot;amp;quot;?o你现在可以使用智能伪原创AI软件百度，你只需要在百度网络资料中找到一篇文章，就可以写一篇文章，然后系统就可以对软文全文最佳答案： 百度的人工智能伪原创效果跟真人差不多，重要的是机器不会出错，更多关于百度伪原创工具的问题>>。
百度秒收的百度伪原百度秒收的百度伪原创工具，随着人工智能技术的发展，以上文章写作过程中遇到的问题都可以采用先进的方法和工具来解决了。人工最佳答案： 百度的人工智能伪原创效果跟真人差不多，重要的是机器不会出错，很符合收录规则更多关于百度伪原创工具的问题>>。
收费伪原创文章工具？?  我来答 分享新浪微博QQ空间举报 1个回答#热议# 新职业在线学习平台发展报告”发布了哪些新趋势？ 诗轩轩轩 20 分钟前最佳答案： 百度是由科研团队数年时间研制而成，自动生成高质量文章，还可以写歌词，诗歌等文章。更多关于百度伪原创工具的问题>>。
绿色先锋下载为您提供百度ai软件免费下载，百度ai软件(专业伪原创写作工具)是一款十分优秀好用的专业伪原创写作辅助工具。如果你想找一款好用的伪用伪原创工具写文章有自媒体文章通常是由平台自动推送的文章。具有鲜明个性的文章通常具有较高的推动率，这意味着它们具有高度的原创性。最近发现一。
其实伪原创也有一些特别简单的方法，就是使用伪原创工具，大部分伪原创工具操作起来都比较简单，只需要找到适合的文章，然后一键操作即可，这类伪原创工具能够在几秒之内来源：重庆seo服务百度AI智能伪原创工具是一个文章仿写程序，它可以像人类一样理解文本，它完全重写句子，并提供人类可读的和完全独特的文章推荐。
百度不只是伪原创工具登陆百度，为你创造热点，创造爆文。千锤百炼，方得一篇佳作；洗尽铅华，才可品味百态傻瓜式采集，自动加锚文本内链，附带伪原创功能 展开详情 采集工具立即下载低至043元/次身份认证VIP会员低至7折收藏举报评论下载该资源后可以。
百度伪原创工具，原创度高达85% 只看楼主收藏回复自媒体黄老师 风悲画角1 送TA礼物 回复1楼2020-05-29 13:16  百度小说人气榜 扫二维码下载贴石青伪原创工具，实际上是一款功能非常强大，而且比较小巧精悍的专业伪原创文章生成器，它同时支持中文和英文两种伪原创模式，而且是完全免费的。这款伪原创工具是专门。
智能伪原创软件也有很多作者说自己天天发文，思维枯竭已经写不出原创的好文章了，所以今天我给大家推荐一个辅助写作的工具“百度”,这是一款人工4、经过10年的研发，百度智能伪原创对文章伪原创领域进行了深入探索，并利用人工智能技术开发了人工智能伪原创文章生成器在线工具。5、百度在线文章生成器是一个完。
';

$content_src = clear_content($content_src); // 过滤特殊字符

$content_newline =my_text2html($title_src, $content_src);


echo $content_newline;
*/

function my_summary1($title) {
	$title = fix_title($title);
	$str = '<p>百度 www.baidu.com 今天跟大家聊聊'.$title.'。</p>';
	return $str;
}



function fix_title($contents) {
    $punctuation_symbol = array('。', '？', '，', '：', '；', '、', '！',
                                '.',  '?',  ',',  ':',  ';', '!');

     $contents = str_replace($punctuation_symbol, '', $contents);
    return $contents;
}

function fix_newline_x($data) {
    $data = str_replace("\r", "\n", $data);
    while(strpos($data, "\n\n") !== false) {
        $data = str_replace("\n\n", "\n", $data);
    }
    $data = str_replace("\n", PHP_EOL, $data);

    return $data;
}


function my_text2html($title, $text) {
	global $h2_rate;
	global $num_rate;
	global $image_rate;
	$text = fix_newline_x($text);
	$data_nl = explode("\n", $text);

	$qitaren = baidu_tuijian($title);

	$p_text = '';
	$is_h2 = (rand(1, $h2_rate) === 1);
	$is_num = (rand(1, $num_rate) === 1);
	$is_chn = (rand(1, 2) === 1);

	$index = 0;
	$rand_img = rand(1, $image_rate);
	//echo '<!-- 6666'.$rand_img.' -->';
	foreach ($data_nl as $key => $value) {
		$rand_color = rand_color();
		if (strlen($value)<4) {
			$value = str_replace(' ', '', $value);
			if (strlen($value)<=1) {
				unset($data_nl[$key]);
			}
			else {
				$p_one = '<p style="color:#'.$rand_color.'">' . $value . '</p>';
				if (rand(1,5)===1) {
					$rand_link = get_rand_post_link_by_kw('编辑');
					$p_one = xfm_strong_str_replace_once('。', $rand_link.'。', $p_one);
				}
				$p_text .= $p_one;
			}
		}
		else {

			if (1 === $rand_img) {
				$rand_img = 99999; // 有一个图片就好了
				#echo 'xxxxxxxxxxxxxxxxx';
				$p_text .= '<p style="color:#'.$rand_color.'">' . $value . '</p>';
				#$p_text .= '<p style="color:green;font-size:17px;">吸猫网编辑小刘：'.$title.'</p><p></p>';
				#$img = get_img($title);
				$img = get_images($title);
				//echo 'img1:';
				//echo $img;
				$p_text .= ''.$img.'';
			}
			else {
				$p_one = '';
				$b = rand(0,3);
				$emoji = '';
				if ($b===2) {
					$emoji = rand_emoji();
					$emoji .= ' ';
				}
				// 随机加 h2 子标题
				// file_put_contents('test.txt', strval(count($qitaren)).' - '.strval($index).' - '.strval($is_h2).' - '.strval($is_num).' - '.strval($is_chn).PHP_EOL, LOCK_EX|FILE_APPEND);
				if (count($qitaren) > 2 && $index > 0 && $is_h2) {
					$sub_title = array_shift($qitaren);
					// 是否加编号
					if ($is_num) {
						if ($is_chn) {
							$p_one = '<h2>' .$index.'、'.$sub_title. '</h2>';
						}
						else {
							$p_one = '<h2>' .numToWord($index).'、'.$sub_title. '</h2>';
						}
					}
					else {
						$p_one = '<h2>' .$sub_title. '</h2>';
					}
				}
				$p_one .= '<p style="font-size:16px;color:#'.$rand_color.'">' . $emoji . $value . '</p>';
				if (rand(1,8)===1) { // 随机插入链接
					if (strpos($title, '猫')) {
						$rand_link = get_rand_post_link_by_kw('猫');
					}
					else if (strpos($title, '狗')) {
						$rand_link = get_rand_post_link_by_kw('狗');
					}
					else if (strpos($title, '犬')) {
						$rand_link = get_rand_post_link_by_kw('狗');
					}
					else {
						$rand_link = get_rand_post_link_by_kw('猫');
					}
					$p_one = xfm_strong_str_replace_once('。', $rand_link.'。', $p_one);
				}
				$p_text .= $p_one;
			}
			$index ++;
		}
	}

	$p_text = str_replace('</p>', '</p>'.PHP_EOL, $p_text);
	return $p_text;
}

function get_rand_post_link_by_kw($kw) {
	return '';
}

function rand_emoji() {
	$str = 'o(╯□╰)o ╯▂╰ ╯０╰ ╯＾╰ ╯ω╰ ╯﹏╰ ╯△╰ ╯▽╰ ＋▂＋ ＋０＋ ＋＾＋ ＋ω＋ ＋﹏＋ ＋△＋ ＋▽＋ ˋ▂ˊ ˋ０ˊ ˋ＾ˊ ˋωˊ ˋ﹏ˊ ˋ△ˊ ˋ▽ˊ ˇ▂ˇ ˇ０ˇ ˇ＾ˇ ˇωˇ ˇ﹏ˇ ˇ△ˇ ˇ▽ˇ ˙▂˙ ˙０˙ ˙＾˙ ˙ω˙ ˙﹏˙ ˙△˙ ˙▽˙ ≡(▔﹏▔)≡ ⊙﹏⊙‖∣° ˋ＾ˊ〉-# ╯＾╰〉 (=｀′=) o(?""?o (ˉ▽ˉ；) (-__-)b ＼　＿　／ ￣□￣｜｜ (#｀′)凸 (｀▽′) ゃōゃ ⊙▂⊙ ⊙０⊙ ⊙＾⊙ ⊙ω⊙ ⊙﹏⊙ ⊙△⊙ ⊙▽⊙ ?▂? ?０? ?＾? ?ω? ?﹏? ?△? ?▽? ∩▂∩ ∩０∩ ∩＾∩ ∩ω∩ ∩﹏∩ ∩△∩ ∩▽∩ ●▂● ●０● ●＾● ●ω● ●﹏● ●△● ●▽● ∪▂∪ ∪０∪ ∪＾∪ ∪ω∪ ∪﹏∪ ∪△∪ ∪▽∪ ≥▂≤ ≥０≤ ≥＾≤ ≥ω≤ ≥﹏≤ ≥△≤ ≥▽≤ ＞▂＜ ＞０＜ ＞＾＜ ＞ω＜ ＞﹏＜ ＞△＜ ＞▽＜ （°ο°） (^人^) (＊?↓˙＊) ↓。υ。↓';

	$arr = explode(' ', $str);
	shuffle($arr);
	return $arr[0];
}


function rand_color() {
	return 'black';
	$str ='000000	000033	000066	000099	0000CC	0000FF
003300	003333	003366	003399	0033CC	0033FF
006600	006633	006666	006699	0066CC	0066FF
009900	009933	009966	009999	0099CC	0099FF
00CC00	00CC33	00CC66	00CC99	00CCCC	00CCFF
00FF00	00FF33	00FF66	00FF99	00FFCC	00FFFF
330000	330033	330066	330099	3300CC	3300FF
333300	333333	333366	333399	3333CC	3333FF
336600	336633	336666	336699	3366CC	3366FF
339900	339933	339966	339999	3399CC	3399FF
33CC00	33CC33	33CC66	33CC99	33CCCC	33CCFF
33FF00	33FF33	33FF66	33FF99	33FFCC	33FFFF
660000	660033	660066	660099	6600CC	6600FF
663300	663333	663366	663399	6633CC	6633FF
666600	666633	666666	666699	6666CC	6666FF
669900	669933	669966	669999	6699CC	6699FF
66CC00	66CC33	66CC66	66CC99	66CCCC	66CCFF
66FF00	66FF33	66FF66	66FF99	66FFCC	66FFFF
990000	990033	990066	990099	9900CC	9900FF
993300	993333	993366	993399	9933CC	9933FF
996600	996633	996666	996699	9966CC	9966FF
999900	999933	999966	999999	9999CC	9999FF
99CC00	99CC33	99CC66	99CC99	99CCCC	99CCFF
99FF00	99FF33	99FF66	99FF99	99FFCC	99FFFF
CC0000	CC0033	CC0066	CC0099	CC00CC	CC00FF
CC3300	CC3333	CC3366	CC3399	CC33CC	CC33FF
CC6600	CC6633	CC6666	CC6699	CC66CC	CC66FF
CC9900	CC9933	CC9966	CC9999	CC99CC	CC99FF
CCCC00	CCCC33	CCCC66	CCCC99	CCCCCC	CCCCFF
CCFF00	CCFF33	CCFF66
FF0000	FF0033	FF0066	FF0099	FF00CC	FF00FF
FF3300	FF3333	FF3366	FF3399	FF33CC	FF33FF
FF6600	FF6633	FF6666	FF6699	FF66CC	FF66FF
FF9900	FF9933	FF9966	FF9999	FF99CC	FF99FF
FFCC00	FFCC33	FFCC66	FFCC99	FFCCCC	FFCCFF
FFFF33	FFFF66';
	$str = str_replace("\r", "", $str);
	$arr_1 = explode("\n", $str);
	$arr_all = array();
	foreach ($arr_1 as $key => $value) {
		$arr_2 = explode('	', $value);
		$arr_all = array_merge($arr_all, $arr_2);
	}
	shuffle($arr_all);
	//return $arr_all;
	return $arr_all[0];
}



/*
$content = str_replace(array('<br>', '　　'), array(PHP_EOL,PHP_EOL), $content);
$contents = strip_tags($content);
*/
//$content = my_clear($content); // 去掉头部尾部无用内容
/*
if (utf8_strlen($content)<=1500) {
	$content = get_baidu($content);
}
*/

//echo stripslashes ( clear_json( strip_tags($content) ) );
/*
$summary = get_summary(
	cut_str(strip_tags($content), 1500)
	);

$keyword = get_keywords(
	cut_str(strip_tags($content), 1500)
	);
	*/
/*
$keyword = '小学,作文,'.$keyword;
$images = get_images($title);
echo $images;
$put_contents = "<p style=\"color:#666666;\">摘要：{$summary}</p><br>";
$put_contents .= $images;
$put_contents .= "<br><p><strong style=\"color:blue\">{$title}</strong></p><br>";
$put_contents .= auto_section($content);


$title = iconv("UTF-8", "GB2312//IGNORE", $title);
$contents = iconv("UTF-8", "GB2312//IGNORE", $put_contents);
$keywords = iconv("UTF-8", "GB2312//IGNORE", $keyword);
*/
//echo $title;
//echo '<hr>';
/*
$contents = auto_section($contents);

$template = file_get_contents('eew.html');

$template = str_replace(
	array(
		'{XEIM_CONTENTS}',
		'{XEIM_TITLE}'
	),
	array(
		$contents,
		$title),
	$template);

echo $template;*/
/*
echo '<pre>';
print_r(iconv("UTF-8", "GB2312//IGNORE", $_POST['title']));
print_r(iconv("UTF-8", "GB2312//IGNORE", $_POST['contents']));
echo '</pre>';
*/
/*
echo dirname(dirname(dirname(__FILE__))).'/aaaaaaaaaaa';

file_put_contents(dirname(dirname(dirname(__FILE__))).'/aaaaaaaaaaa', $title);
file_put_contents(dirname(dirname(dirname(__FILE__))).'/bbbbbbbbbbb', $contents);
file_put_contents(dirname(dirname(dirname(__FILE__))).'/ccccccccccc', $keywords);
*/



//echo file_get_contents('http://'.$argv[2].'/uxxbu.php');

//update_article_info($table_name, $listid);


function clean_title($title) {
	$replace = '百度';
	$arr = array('文芳阁','爱发狗');

	foreach ($arr as $key => $value) {
		# code...
		$title = str_replace($value, $replace, $title);
	}

	return $title;
}


function get_images($title) {

	$my_images = get_ai_images($title);
	if ($my_images === '') {
		return '';
	}

    $images = '<p><img src="'.$my_images.'" title="'.$title.'" alt="'.$title.'" /></p>';
    return $images;
}


function auto_section($content) {
	$str=trim($content); // 取得字串同时去掉头尾空格和空回车
 //$str=str_replace("<br>","",$str); // 去掉<br>标签
 $str="<p>".trim($str); // 在文本头加入<p>
 $str=str_replace(PHP_EOL,"</p>\n<p>",$str); // 用p标签取代换行符
 $str.="</p>\n"; // 文本尾加入</p>
 $str=str_replace("<p></p>","",$str); // 去除空段落
 $str=str_replace(PHP_EOL,"",$str); // 去掉空行并连成一行
 $str=str_replace("</p>","</p>".PHP_EOL,$str); //整理html代码

 return $str;
}

/*
var_dump($listid);
exit;
*/

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
  if($code == 'UTF-8')
  {
    $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($pa, $string, $t_string);
  
    if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
    return join('', array_slice($t_string[0], $start, $sublen));
  }
  else
  {
    $start = $start*2;
    $sublen = $sublen*2;
    $strlen = strlen($string);
    $tmpstr = '';
  
    for($i=0; $i< $strlen; $i++)
    {
      if($i>=$start && $i< ($start+$sublen))
      {
        if(ord(substr($string, $i, 1))>129)
        {
          $tmpstr.= substr($string, $i, 2);
        }
        else
        {
          $tmpstr.= substr($string, $i, 1);
        }
      }
      if(ord(substr($string, $i, 1))>129) $i++;
    }
    if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
    return $tmpstr;
  }
}

function get_keywords($news, $max=3) {
	/*
	global $bnlp;
	$response= $bnlp->keywords(BosonNLP::removePunct($news."\""), 10);
    $response = json_decode($response['response'], true);
	$ret = '';
	$count = 0;
	foreach ($response as $key=>$value) {
		if ($count++ < $max){
			$ret .= $value[1].',';
		}
	}
*/
	$ret = '';
	return trim($ret, ',');
}

function get_summary($content) {
	global $bnlp;
//	$summary = $bnlp->summary(BosonNLP::removePunct($content),"", 80);
	$summary = $content;
	return $summary;
//	return str_replace(array('"',' ','\n'), array('', '', ''), $summary['response']);
}

function get_BosonNLP_tag($data, $type) {
	$pass_type = array('nr','ns','nt', 'nz', 'nl');
	foreach ($data['tag'] as $key => $value) {
		//if (stripos($value, $type) !== false){
		if (in_array($value, $pass_type)) {
			echo $data['word'][$key].', ';
		}
	}
}

function clear_content($content) {
	$content = str_replace(array('99zuowen', 'www.99zuowen.com', '99作文网', 'ff0000', '<br>', '<br />', 'ifagou', '5118'),
						array('baidu','www.baidu.com', '游吧看吧作文网', 'f26c4f', PHP_EOL, PHP_EOL, 'baidu', '百度'),
						$content);

	return $content;
}

function utf8Substr($str, $from, $len)
{
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
            '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
            '$1',$str);
}

function update_article_info($table_name, $id) {
	global $mysql_link;
	$sql = "UPDATE `{$table_name}` SET `catename3`='ok' WHERE listid={$id}";
	$retval = mysqli_query($mysql_link, $sql);
	if (! $retval)
	{
	    //die('无法更新数据: ' . mysqli_error($conn));
	    return false;
	}
	//echo '数据更新成功！';
	return true;
}

function get_article_by_id($id, $table_name) {
	global $mysql_link;
	$sql = "select * from {$table_name} where `listid`={$id}";
var_dump($sql);
	$result = mysqli_query($mysql_link, $sql);
	if (mysqli_num_rows($result)!==0){
		return mysqli_fetch_assoc($result);
	}
}

function clear_json($str) {

	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                 "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记
                 "'([\r\n])[\s]+'",                 // 去掉空白字符
                 "'&(quot|#34);'i",                 // 替换 HTML 实体
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(copy|#169);'i",
                 "'&#(\d+);'e"); // 作为 PHP 代码运行

	$replace = array ("","",
	"\\1",
	"\"",
	"&",
	"<",
	">",
	" ",
	chr(161),
	chr(162),
	chr(163),
	chr(169),
	"chr(\\1)");

	$text = preg_replace ($search, $replace, $str);
	return $text;
}





function get_list_by_page($table_name, $page) {
	global $mysql_link;
	$begin = 10*($page-1);
	$end = 10*$page;

	$sql = "select listid, title, content from {$table_name} where content NOT NULL limit {$begin},{$end}";
//	echo $sql;
	$result = mysqli_query($mysql_link, $sql);

	if ($result) {

		$rows = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}
	else{
		echo mysql_error();
	}
	return false;
}


function get_remaining($table_name) {
	global $mysql_link;
	$sql = "select count(*) remaining from {$table_name} where catename3 IS NULL";
//	echo $sql;
	$result = mysqli_query($mysql_link, $sql);
//	pre_print($result);
	if ($result) {
		if (mysqli_num_rows($result)!==0){
			$retdata = mysqli_fetch_assoc($result);
			return $retdata['remaining'];
		}
	}
	else{
		echo mysql_error();
	}
	return 0;
}

function get_total($table_name) {
	global $mysql_link;
	$sql = "select count(*) remaining from {$table_name}";
//	echo $sql;
	$result = mysqli_query($mysql_link, $sql);
//	pre_print($result);
	if ($result) {
		if (mysqli_num_rows($result)!==0){
			$retdata = mysqli_fetch_assoc($result);
			return $retdata['remaining'];
		}
	}
	else{
		echo mysql_error();
	}
	return 0;
}

/**
*自动判断把gbk或gb2312编码的字符串转为utf8 
*能自动判断输入字符串的编码类，如果本身是utf-8就不用转换，否则就转换为utf-8的字符串 
*支持的字符编码类型是：utf-8,gbk,gb2312 
*@$str:string 字符串 
*/
function yang_gbk2utf8($str){
    $charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312'));
    $charset = strtolower($charset); 
    if('cp936' == $charset){
        $charset='GBK';
    }
    if("utf-8" != $charset){ 
        $str = iconv($charset,"UTF-8//IGNORE",$str); 
    } 
    return $str; 
}



function xfm_strong_str_replace_once($search, $replace, $subject) {
    $firstChar = strpos($subject, $search);
    if($firstChar !== false) {
        $beforeStr = substr($subject,0,$firstChar);
        $afterStr = substr($subject, $firstChar + strlen($search));
        return $beforeStr.$replace.$afterStr;
    } else {
        return $subject;
    }
}

function get_img($title) {
	$PHP_SELF = isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:"";
	$PHP_QUERYSTRING = $_SERVER['QUERY_STRING'];
	$PHP_DOMAIN = $_SERVER['SERVER_NAME'];
	$PHP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$PHP_SCHEME = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$PHP_PORT = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
	$PHP_SITEURL = $PHP_SCHEME.$PHP_DOMAIN.$PHP_PORT;
	$PHP_URL = $PHP_SCHEME.$PHP_DOMAIN.$PHP_PORT.$PHP_SELF.($PHP_QUERYSTRING ? '?'.$PHP_QUERYSTRING : '');

	$src = get_news_list('images/800x600');
	#$src = str_replace('\\', '/', $src);
	$src = dirname($PHP_URL).$src;
	return '<img src="'.$src.'" alt="'.$title.'" title="'.$title.'">';
}

function get_news_list($floder) {
    $news_list = array();
    if($handle = opendir($floder)){
      while (false !== ($file = readdir($handle))){
        //$dir = iconv("utf-8", "gb2312", $file);
        $file = iconv("gb2312", "utf-8", $file);
        if ($file !== '..' && $file !== '.')
        {
            $news_list[] = $floder.DIRECTORY_SEPARATOR.$file;
        }

    //    echo "$file\n";
      }
      closedir($handle);
    }
    shuffle($news_list);
    return '/'.$news_list[0];
}


function numToWord($num)
{
	$chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
	$chiUni = array('','十', '百', '千', '万', '亿', '十', '百', '千');

	$chiStr = '';

	$num_str = (string)$num;

	$count = strlen($num_str);
	$last_flag = true; //上一个 是否为0
	$zero_flag = true; //是否第一个
	$temp_num = null; //临时数字

	$chiStr = '';//拼接结果
	if ($count == 2) {//两位数
		$temp_num = $num_str[0];
		$chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num].$chiUni[1];
		$temp_num = $num_str[1];
		$chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
	}else if($count > 2){
		$index = 0;
		for ($i=$count-1; $i >= 0 ; $i--) {
			$temp_num = $num_str[$i];
			if ($temp_num == 0) {
				if (!$zero_flag && !$last_flag ) {
					$chiStr = $chiNum[$temp_num]. $chiStr;
					$last_flag = true;
			}
			}else{
				$chiStr = $chiNum[$temp_num].$chiUni[$index%9] .$chiStr;

				$zero_flag = false;
				$last_flag = false;
			}
			$index ++;
		}
	}else{
		$chiStr = $chiNum[$num_str[0]];
	}
	return $chiStr;
}
?>