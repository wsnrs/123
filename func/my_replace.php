<?php



function my_replace($article) {
    $arr = array('中国'=>'中文',
                '福利'=>'食物',
                '权力'=>'义务',
                '国家'=>'家园',
                '政治'=>'规律',
                '公权力'=>'多数人',
                '社会'=>'地球村');
    foreach($arr as $key=>$value) {
        $article = str_replace($key, $value, $article);
    }

    return $article;
}