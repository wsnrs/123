<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'article_punctuation.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'chengyu.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'yanyu.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'text2html.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'baidu_sugg.php');

$zh_explode_debug = true;
/*
$article = '我们如何做个人媒体的好工作？这些天我一直在考虑这个！
我的自我媒体已经间歇性地做了几年，但我没有这样做。在过去的几天里，我转身回头，分析了主要问题。如下：
1.帖子非常随意。它涉及许多领域。也许许多人不知道我在做什么。没有明显的特征，也不能反映特定领域的专业精神。自然，它没有吸引力。
2.该职位不规则，并停止了很长时间。这样的状态不得做到。自我媒体仍然必须持续存在。毕竟，这是业余爱好者。即使很难发布一天，每周至少必须发出一定数量的文本。
3.发表帖子以吸引人们。文章中必须有干货，以便其他人可以看到收益或共鸣，否则浪费时间。此外，有必要吸引人们，共鸣并专注于采用各种新形式，例如小型视频，例如小型视频，短暂和快速，并且可以掌握眼球。
基于上述分析，我有以下计划，如何做自媒体的良好工作！同时，我希望我的计划对也是自媒体人的朋友有所作为！
1.确保有清晰的定位
这个自媒体做什么？你想通过什么？别人可以带给他人什么？对他人的价值是什么？这些是第一个被考虑的人！并确保清楚地考虑。
在这一点上，您可以首先分析更受欢迎的自我媒体，或传达诸如媒体或娱乐活动之类的自我媒体。基本上，有明确的定位。那么我们自己的自我介绍应该如何呢？你做什么方向？
从他们自己专业知识的特征开始，以确定媒体的方向。例如，我从事教学和培训多年，现在我正在从事农村振兴和工程咨询工作。因此，我的自我媒体一直基于更熟悉的教师资格考试培训，工程顾问培训和其他研究以及农村振兴培训。主要是成为知识传播的自我媒体。
在这里，我也希望对相关培训更感兴趣的朋友可以跟随！
2.坚持发布和发布视频
我的自我媒体在今年上半年发行了高频。当时，它在短短两个或三个月内增长了6,000多个。之后，我经常停下来。文章和视频正在这样做。粉丝甚至增加了。
从去年到今年，工作变化，离开学校，改变咨询公司和计划公司，已经很长时间了，他们很忙。我没有在业余时间调整自己。因此，本质是要坚持发布并遵守定位方向的定位，这是要做的。
3.从中受益和宝贵的东西
根据您自己的导演的价值，应使用视频和发送视频的价值来反映各种形式的传达内容的吸引力。
这需要一些诚意和技能。我相信我将来会逐渐做到。最后，给自己一个小目标。在2022年，粉末的目标是10,000，收入目标是10,000。这个目标难度不难吗？等一下，我会随时分享它并与所有人见证！我希望跟随！';


var_dump(ai_jason($article));*/

function my_spanc($str, $color) {
    global $zh_explode_debug;
    if ($zh_explode_debug) {
        return '<span style="color:'.$color.';">'.$str.'</span>';
    }
    return $str;
}

function my_span($str) {
    global $zh_explode_debug;
    if ($zh_explode_debug) {
        return '<span style="color:red;">'.$str.'</span>';
    }
    return $str;
}

function my_span2($str) {
    global $zh_explode_debug;
    if ($zh_explode_debug) {
        return '<span style="color:blue;">'.$str.'</span>';
    }
    return $str;
}


# 让成语看起来更自然
function language_jason($str) {
    $arr = ['或','可能','会','也许','大概','没准','似乎','好像','估计','差不多','或许','是否','或者','要么','兴许','只能说','有点'];
    shuffle($arr);
    return $arr[6] . $str;
}


function ai_jason($article) {
    $article_arr = my_explode($article);
    #var_dump($article_arr);
    $chengyu = read_chengyu();
    $yanyu = read_yanyu();
    shuffle($chengyu);
    shuffle($yanyu);

    $ret_str = '';
    foreach($article_arr as $value) {

        $is_sugg = false;
        if (strpos($value, '，') !== false)
        {
            if (! $is_sugg) {
                $suubb = explode('，', $value);
                #var_dump($suubb);
                if (mb_strlen($suubb[0])>5) {
                    $sugg = my_get_suggestion($suubb[0]);
                    if ($sugg) {
                        $is_sugg = true;
                        $new_str = my_spanc($sugg[0], 'green');
                        $value = str_replace($suubb[0], $new_str.'`*`'.$suubb[0], $value);
                    }
                }
            }


            while (substr_count($value, '，')) {

                $one_chengyu = my_span(
                    language_jason(
                        array_pop($chengyu)
                    )
                );

                $value = xfm_strong_str_replace_once('，', $one_chengyu.'`*`', $value);
            }

            if (strpos($value, '。') !== false) {
                $one_yanyu = my_span2(array_pop($yanyu));
                $value = xfm_strong_str_replace_once('。', '，'.$one_yanyu, $value);
            }
            else if (strpos($value, '？') !== false) {
                $one_yanyu = my_span2(array_pop($yanyu));
                $value = xfm_strong_str_replace_once('。', '，'.$one_yanyu, $value);
            }
            else if (strpos($value, '！') !== false) {
                $one_yanyu = my_span2(array_pop($yanyu));
                $value = xfm_strong_str_replace_once('。', '，'.$one_yanyu, $value);
            }

            $ret_str .= $value;
        }
        else {
            #var_dump($value);
            #var_dump(strpos($value, '？'));
            if (strpos($value, '。') !== false) {
                $one_yanyu = my_span2(array_pop($yanyu));
                $value = xfm_strong_str_replace_once('。', '，'.$one_yanyu, $value);
            }
            else if (strpos($value, '？') !== false) {
                $one_yanyu = my_span2(array_pop($yanyu));
                #var_dump($one_yanyu);
                $value = xfm_strong_str_replace_once('？', '？'.$one_yanyu, $value);
            }
            else if (strpos($value, '！') !== false) {
                $one_yanyu = my_span2(array_pop($yanyu));
                $value = xfm_strong_str_replace_once('！', '！'.$one_yanyu, $value);
            }
            $ret_str .= $value;
        }
    }

    return str_replace('`*`', '，', $ret_str);
}


# 按句子来分割。
function my_explode($article) {
    # 先修复下标点符号
    list($article_fixed, $ret_word_arr, $changes) = fix_punctuaction($article);
    $period = get_chinese_period();
    foreach($period as $key=>$value) {
        $article_fixed = str_replace($value, $value.'```', $article_fixed);
    }
    $article_fixed = str_replace("\n", "\n".'```', $article_fixed);

    return explode('```', $article_fixed);
}


if (! function_exists('xfm_strong_str_replace_once')) {
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
}


# 中文句子结尾
function get_chinese_period() {
    $arr = array('。', '！', '？');
    return $arr;
}


?>