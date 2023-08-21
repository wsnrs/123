<?php

if (! isset($GLOBALS['ai_image'])) {
    $ai_image = '';
}

# 给文章自动加入相关图片
function article_nice_img($article, $title) {
    global $ai_image;
    $img = '';
    if ($ai_image !== '') {
        $img = "<p class='xfmimg'><img src='".$ai_image.$title.".jpg' alt='".$title."' title='".$title."' /></p>".PHP_EOL;
    }

    return $img.$article;
}



?>