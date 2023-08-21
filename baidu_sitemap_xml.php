<?php
header("Content-type: text/xml");
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
require_once('xfm_config.php');

bd_sitemaps();

// 生成百度 sitemap
function bd_sitemaps()
{
    $home_url = '';
    if (cfg_is_https()) {
        $home_url = 'https://'.$_SERVER['SERVER_NAME'];
    }
    else {
        $home_url = 'http://'.$_SERVER['SERVER_NAME'];
    }
    global $keyword_list;
    $sitemap_data = '';
    $sitemap_data .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>".PHP_EOL;
    $sitemap_data .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
    $date_cur = date('Y-m-d');
    $sitemap_data .= '    <url>
        <loc>'.$home_url.'</loc>
        <lastmod>'.$date_cur.'</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>'.PHP_EOL;


    $all_keywords = get_keyword($keyword_list);
    shuffle($all_keywords);
    $index = 0;
    foreach ($all_keywords as $keyword) {
        if ($index >= 300) break;
        xfm_put_keyword_cache($keyword);
        // 获取关键词短网址
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url);
        if (cfg_is_https()) {
            $domain_url = str_replace('http://', 'https://', $domain_url);
        }
        $sitemap_data .= '    <url>
        <loc>'.$domain_url.'</loc>
        <lastmod>'.$date_cur.'</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.4</priority>
    </url>'.PHP_EOL;
        $index++;
    }
/*
    foreach ($datas as $k => $v) {
        $url = xxxx;

        echo '
        <url>
        <loc>' . $url . '</loc>
        <priority>0.5</priority>
        <lastmod>' . date("Y-m-d", time()) . '</lastmod>
        <changefreq>daily</changefreq>
        </url>
        ';
    }*/

    $sitemap_data .= "</urlset>";

    echo $sitemap_data;
    file_put_contents('sitemap.xml', $sitemap_data);
}

?>