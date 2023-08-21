<?php
require_once ('xfm_config.php');
require_once("func".DIRECTORY_SEPARATOR.'word_base.php');
require_once('func'.DIRECTORY_SEPARATOR.'xfm_txt_func.php');
require_once ('xfm_images.php');
require_once ('xfm_news.php');

$today_list =  get_today_list(); // 当天更新的文章
//var_dump($today_list);
$images_list = get_local_images(); // 图片列表
$images_list_rand = $images_list;

shuffle($images_list_rand);

//shuffle($images_list); // 打乱顺序

$news_list = get_index_news_list(); // 文章列表
//var_dump($news_list);
function update_index($template) {
  global $news_list;
  global $images_list_rand;
  // $template = file_get_contents($template_dir.DIRECTORY_SEPARATOR.'index_template.html');
  $xfm_user_tags = get_all_tag($template);

  if ($xfm_user_tags) {
    foreach($xfm_user_tags as $value) {
      //var_dump($value[0]);
      $template = process_user_tag($value[0], $template);
    }
  }

  # 循环插入不同的随机日期
  while (strpos($template, '{XFM_RAND_DATE}') !== false) {
    $time_rand_week = get_rand_time_week();
    $template = xfm_news_str_replace_once('{XFM_RAND_DATE}', $time_rand_week, $template);
  }


    # 循环插入不同的图片
    while (strpos($template, '{XFM_RAND_IMAGE}') !== false) {
      $template = xfm_news_str_replace_once('{XFM_RAND_IMAGE}', array_shift($images_list_rand), $template);
    }

  return $template;
}


# 插入随机日期
function process_time_tag($template) {

}
#echo $template;
//var_dump($xfm_user_tags);



// 处理每一个用户自定义TAG
// 每次处理一个TAG，例如 {{index_template_list_1.html, 5}}
// 填充好数据后返回 $template
function process_user_tag($tag, $template) {
  global $news_list;
  global $template_dir;
  global $images_list;
  global $images_list_rand;

  $tmp = str_replace(array('{{', '}}', ' '), '', $tag);
  $tmp_arr = explode(',', $tmp);

  $replace_time = isset($tmp_arr[1])?intval($tmp_arr[1]):1;

  $sub_template_data = '';
  if (isset($tmp_arr[0])) {

    $sub_template = $template_dir.DIRECTORY_SEPARATOR.$tmp_arr[0];
    if (!file_exists($sub_template)) {
      echo '模板错误：'. $tag;
    }
    else {
      $sub_template_str = file_get_contents($sub_template);
      for($i=0; $i<$replace_time; $i++) {
        if (count($news_list)<1) {
          break;
        }
        $temp_one_list = array_shift($news_list);
        /*if (! $temp_one_list) {
          break;
        }*/
        $one_list = unserialize($temp_one_list);
        $one_rand_images = str_replace('\\', '/', array_shift($images_list_rand));
        $one_images = str_replace('\\', '/', array_shift($images_list));
        $time_rand_week = get_rand_time_week();
        $time_short_md = date('m-d', strtotime($one_list['time']));
        $rand_count = strval(rand(10, 5000));
        //var_dump($one_list);
        $sub_template_data .= str_replace(
            array('{HREF}',           /* 1 */
            '{TEXT}',                 /* 2 */
            '{SUMMARY}',              /* 3 */
              '{RAND_IMAGES}',        /* 4 */
              '{IMAGES}',             /* 5 */
              '{XFM_TIME_LIST_S}',    /* 6 */
              '{COUNT}',              /* 7 */
              '{XFM_TIME_LIST}'),     /* 8 */

            array($one_list['url'],     /* 1 */
            $one_list['title'],         /* 2 */
            $one_list['description'],   /* 3 */
            $one_rand_images,           /* 4 */
            $one_images,                /* 5 */
            $time_short_md,             /* 6 */
            $rand_count,                /* 7 */
            $one_list['time']),         /* 8 */

            $sub_template_str);     
      }

      #var_dump($sub_template_data);
    }
  }
  else {
    echo '模板错误：'. $tag;
  }

  $template = str_replace($tag, $sub_template_data, $template);

  #var_dump($tmp_arr);

  return $template;
}


// 读取模板的自定义TAG
function get_all_tag($_template)
{
  $re = '/\{\{.*\}\}/m';

  preg_match_all($re, $_template, $matches, PREG_SET_ORDER, 0);

  //var_dump($matches);
  // Print the entire match result
  if (isset($matches[0]))
    return $matches;

  return false;
}

#spider_keyword_to_index_list();
# 把蜘蛛关键词转成首页列表
function spider_keyword_to_index_list() {
  global $spider_keyword_arr;
  #var_dump($spider_keyword_arr);
  $index_arr = array();
  $count = $spider_keyword_arr;
  $min = min(INDEX_MAX_LIST, $count);
  for($i=0; $i<$min; $i++) {
    $time = time();
    $time = $time - rand(8640, 86400);
    $ccc = array(
      "title"=>$spider_keyword_arr[$i],
      "description"=>$spider_keyword_arr[$i],
      "url"=>get_domain_url(short_md5($spider_keyword_arr[$i])),
      "time"=>date('Y-m-d H:is', $time)
    );
    xfm_put_keyword_cache($spider_keyword_arr[$i]);
    $index_arr[] = serialize($ccc);
  }

  return $index_arr;
}

# 读取首页新闻列表
# 2021-4-16 更新，之前只读取 index_list.txt 的列表，更新后，会读取当天触发更新的列表。
/*
    $date_file = date('Y-m-d');
    $data = serialize(array('title'=>$_title, 'description'=>$_description, 'url'=>$_url, 'time'=>$_time));
    $data .= PHP_EOL;
    */
function get_index_news_list() {
  global $shuju_dir;
  global $auto_update_index;
  global $today_list;
  global $keyword_list;

  $index_list_file = $shuju_dir.DIRECTORY_SEPARATOR.'index_list.txt';

  // 首页列表为空
  if (! file_exists($index_list_file)) {
      //header('location: user_push.php');
      return spider_keyword_to_index_list();
  }
/*
  $data = str_replace("\r\n", "\n", $data);
  $data = str_replace("\r", "\n", $data);
  while (strpos($data, "\n\n") !== false) {
      $data = str_replace("\n\n", "\n", $data);
  }
  $data_arr = explode("\n", $data);*/
  $data_arr = xfm_txt_to_array($index_list_file);

  // 写满18个首页链接，就不继续写了。
  if (count($data_arr) > INDEX_MAX_LIST) {
      file_put_contents(INDEX_LIST_LOCK, '1');
  }
  #var_dump($data_arr);
  //array_pop($data_arr);
  // 是否显示最新文章
  if ($auto_update_index) {
    if ($today_list) {
      $data_arr = array_merge($today_list, $data_arr);
    }
  }

  if (count($data_arr) < INDEX_MAX_LIST) {
    $data_arr = array_merge(spider_keyword_to_index_list(), $data_arr);
  }

#var_dump($data_arr);

  return array_reverse($data_arr);
}

// 读取今天的更新列表
function get_today_list() {
  global $shuju_dir;
  $date_file = date('Y-m-d');
  $today_file = $shuju_dir.DIRECTORY_SEPARATOR.$date_file.'_list.txt';
  if (file_exists($today_file)) {
    $data = file_get_contents($today_file);
    $data = api_fix_newline($data);
    $data_arr = line_to_array($data);
    return array_reverse($data_arr);
  }
  return false;
}


// 单行文件转数组
function line_to_array($data) {
  $data = api_fix_newline($data);
  $data_arr = explode("\n", $data);
  $last = array_pop($data_arr);
  if (strlen($last)>0) {
    $data_arr[] = $last;
  }

  return $data_arr;
}


?>