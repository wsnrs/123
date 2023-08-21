<?php
require_once("xfm_config.php");
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once("xfm_tag_parser.php");



// 安装完成删除文件列表
$remove_file_list = array('proxy_curl_get_test.php', 
'xfm_simple_cache.v.2_remove_old.php',
'config.php', 
'bom.php', 
'preview.png', 
'xfm_reset_all.php',
'sp_kw_reset.php',
'user_href.php');


if (isset($_GET['rename']) && $_GET['rename']==='1') {
    foreach ($remove_file_list as $value) {
        echo '文件名：'.$value;
        echo '，';
        if (file_exists($value)) {
            echo span_color('存在', 'red');
            if (is_writable($value)) {
                unlink($value);
                echo '已删除';
            }
            else {
                echo '无删除权限，请手动删除';
            }
        }
        else {
            echo span_color('不存在');
        }
        echo '<br>'.PHP_EOL;
    }
    exit;
}


echo '<p><a href="create_sitemap.php" target="_blank">点击这里生成sitemap.xml，生成一次即可。</a></p>';
// 如果首页链接写满了，就不需要继续写了，写满时候 18个。
if (file_exists($index_lock_file)) {
    echo '自动启动完成<br>';
    echo '如果确认系统配置好不再修改，请手动删除或者重命名 xfm_reset_all.php autorun.php config.php，或者 <a href="autorun.php?rename=1">自动删除</a>';
    exit;
}
else {
    echo '<iframe src="user_push.php" width=100 height=100 id="job_frame"></iframe>';
    echo '<script language=JavaScript>';
    //echo 'setInterval(function(){document.getElementById("job_frame").contentWindow.location.reload(true);}, 5000);';
    echo 'setInterval(function(){window.location.reload(true);}, 5000);';
    echo '</script>';
}


$completed = isset($news_list)?count($news_list):0;
echo '<p><a href="index.php" target="_blank">预览首页</a></p>';
echo '<p style="color:red;">执行完毕，记得从服务器删除这个文件。';
echo '<p>这个过程需要等待大概10分钟，可以挂机，然后去做其他事情。';
echo '<p>总任务：' . $max_index_list;
echo '</p>';
echo '<p>已完成：' . $completed;
echo '</p>';
echo '<p>PHP版本：'.PHP_VERSION.'</p>';

if (is_writable($shuju_dir)) {
    echo '<p>'.$shuju_dir. '可写入'.'</p>';
}
else {
    echo '<p>'.$shuju_dir. '不可写入'.'</p>';
}

if (is_writable($config_dir)) {
    echo '<p>'.$config_dir. '可写入'.'</p>';
}
else {
    echo '<p>'.$config_dir. '不可写入'.'</p>';
}

if (!isset($_SERVER['SERVER_SOFTWARE'])) {

    echo '未检测到服务器类型';
    
    }
    
    $webServer = strtolower($_SERVER['SERVER_SOFTWARE']);
    
    if (strpos($webServer, 'apache') !== false) {
    
    echo 'apache服务器';
    
    } elseif (strpos($webServer, 'microsoft-iis') !== false) {
    
    echo 'iis服务器';
    
    } elseif (strpos($webServer, 'nginx') !== false) {
    
    echo 'nginx服务器';
    
    } elseif (strpos($webServer, 'lighttpd') !== false) {
    
    echo 'lighttpd服务器';
    
    } elseif (strpos($webServer, 'kangle') !== false) {
    
    echo 'kangle服务器';
    
    } elseif (strpos($webServer, 'caddy') !== false) {
    
    echo 'caddy服务器';
    
    } elseif (strpos($webServer, 'development server') !== false) {
    
    echo 'development server 服务器';
    
    } else {
    
    echo $webServer;
    
    }

function span_color($str, $color='blue') {
    return '<span style="color:'.$color.'">'.$str.'</span>';
}

?>