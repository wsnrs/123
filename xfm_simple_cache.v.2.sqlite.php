<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
#require_once('baidu_push_api.php');
require_once("3rd".DIRECTORY_SEPARATOR."short_url.php");
require_once('xfm_news.php');           // spider.txt 里的数据
define('BASE_DOMAIN', 'http://'.$_SERVER['SERVER_NAME'].'/');
// 主动执行百度推送

$is_rewrite = true;     // 是否伪静态 true 开，false 关

$ttt = new xfm_cache_sqlite('test.db');
//var_dump($ttt->table_exists('xfm_article'));
//var_dump($ttt->save_article('aaaaaaaa', '床前明月光', '疑是地上霜', '静夜思'));
$ttt->get_article('aaaaaaaa');
//$ttt->createTable_cache();
//$ttt->createTable();
//$ttt->insertData();
//$ttt->FetchData();
exit;
//$db = new SQLite3('mysqlitedb.db');
//$db->exec('insert into table values("name", "teststring")');

//$re = $db->query('select name from table');
//while ($row = $re->fetchArray()){
    //var_dump($row);
    //print_r($row);
//}




//echo '/*'.PHP_EOL;
get_all_keyword_href();

//echo PHP_EOL.'*/';


// 获取spider.txt里所有链接 <a href=""></a>
function get_all_keyword_href() {
    $all_keywords = get_keyword('config'.DIRECTORY_SEPARATOR.'spider.txt');

    foreach ($all_keywords as $keyword) {
        xfm_put_keyword_cache($keyword);
        // 获取关键词短网址
        $short_url = short_md5($keyword);
        $domain_url = get_domain_url($short_url);
        echo $domain_url.PHP_EOL;
        //echo '1:<br>';
        //header('location: '. $domain_url);
    }

}


function push_one_keyword($keyword) {
    xfm_put_keyword_cache($keyword);
    // 获取关键词短网址
    $short_url = short_md5($keyword);
    $domain_url = get_domain_url($short_url);
    echo '1:<br>';
    var_dump($domain_url);
    #$tmp_self_url = get_domain_url('');
    #$tmp_self_url = str_replace($tmp_self_url, BASE_DOMAIN, $domain_url);
    #echo '<br>2:<br>';
    #var_dump('location: '. $tmp_self_url);
    header('location: '. $domain_url);
    #return $tmp_self_url;
}


function get_one_keyword() {
    $kws = get_keyword('spider2.txt');
    return $kws[0];
}


/*
echo '/*'.PHP_EOL;
if (xfm_get_keyword_exists($url)) {
    echo $url;
    echo ' 已推送';
}
else {
    echo '推送URL：'.$url;
    $push_ret = my_baidu_push($url, $site);
    if (strpos($push_ret, 'success') !== false) {
        xfm_put_keyword_cache($url);
        echo ' cached. ';
        echo $push_ret;
    }
    else {
        echo $push_ret;
    }
    //my_baidu_push('http://www.paperbert.com/ask-%E5%93%AA%E9%87%8C%E5%8F%AF%E4%BB%A5%E5%85%8D%E8%B4%B9%E8%AE%BA%E6%96%87%E9%99%8D%E9%87%8D.html');
}
*/
//echo PHP_EOL.'*/';


// 缓存管理
class xfm_cache_sqlite
{
    private $file;
    private $sqLiteOb;

    public function __construct($db_file)
    {
        $dir = './Sqlite';
        if (!is_dir($dir)){
            mkdir($dir);
        }
        //$file = $dir.'/'.md5(time()).'.db';
        $file = $dir.DIRECTORY_SEPARATOR.$db_file;
        if (!file_exists($file)){
            $fp = fopen($file, 'w');
            if (!$fp){
                throw new \Exception('文件'.$dir.'创建失败');
            }
            fclose($fp);
        }
        $this->file = $file;
        $this->sqLiteOb = new \SQLite3($file);

        if (! $this->table_exists('xfm_article')) {
            //echo 'do not create';
            $this->createTable_xfm_article();
        }
        else {
            //echo 'what happened.';
        }
    }

    function __destruct(){
        $this->sqLiteOb->close();
    }

    // 建表
    function createTable_xfm_article(){
        $table_sql = <<<EOF
        CREATE TABLE [xfm_article](
            [id] integer PRIMARY KEY autoincrement, 
            [shorturl] TEXT, 
            [title] NTEXT, 
            [content] NTEXT, 
            [tags] NTEXT);
EOF;

        $ret = $this->sqLiteOb->exec($table_sql);
        if(!$ret){
            throw new \Exception($this->sqLiteOb->lastErrorMsg());
        }
    }


    public function get_article($shorturl) {
        $sql = "select * from 'xfm_article' where shorturl='{$shorturl}';";
        //var_dump($sql);
        $re = $this->sqLiteOb->query($sql);
        //var_dump($re);
        $rows = [];
        while ($row = $re->fetchArray(SQLITE3_ASSOC)) {
            //var_dump($row);
            $rows[] = $row;
            //var_dump($row);
            //print_r($row);
        }
        return $rows;
    }

    public function save_article($shorturl, $title, $content, $tag) {
        $sql = "insert into 'xfm_article' ('shorturl', 'title', 'content', 'tags') values ('{$shorturl}', '{$title}', '{$content}', '{$tag}')";
        $ret = $this->sqLiteOb->exec($sql);
        return $ret;
    }

    public function table_exists($table_name)
    {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='{$table_name}'";
        $result = $this->sqLiteOb->querySingle($sql);
        //var_dump($result);
        if (is_null($result)) {
            return false;
        }
        return true;
    }

    public function insert_article($title, $content, $tags) {
        $data = [
            [1, '小明', 15],
            [2, '小红', 14],
            [3, '小黄', 16],
        ];
        $insert = '';
        foreach ($data as $v) {
            $insert = "UNION ALL SELECT '{$v[0]}','{$v[1]}', '{$v[2]}'";
        }
        $insert = substr($insert, 9);
        $this->sqLiteOb->exec("insert into xfm_article ".$insert);
    }

    public function insertData(){
        $data = [
            [1, '小明', 15],
            [2, '小红', 14],
            [3, '小黄', 16],
        ];
        $insert = '';
        foreach ($data as $v) {
            $insert = "UNION ALL SELECT '{$v[0]}','{$v[1]}', '{$v[2]}'";
        }
        $insert = substr($insert, 9);
        $this->sqLiteOb->exec("insert into base_feetitemareasqlite ".$insert);
    }

    public function FetchData() {
        $re = $this->sqLiteOb->query('select * from xfm_article');
        var_dump($re);
        while ($row = $re->fetchArray()) {
            var_dump($row);
            //print_r($row);
        }
    }
}

?>