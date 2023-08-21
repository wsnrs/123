<?php

# 页面URL管理，可支持伪静态规则

#var_dump(get_self_url());
#var_dump(get_domain_url('xxxxx'));


# 判断是否伪静态
function is_write() {
    #return true;
    global $is_rewrite;
    return $is_rewrite;
}


# 获取当前域名
function get_domain_url($short_url, $type=1) {
    global $url_rewrite_type;
    global $menu_folder;
    $return_url = '';
    $self_url = get_self_url();
    if (is_write()) {   // 伪静态条件下
        //var_dump('is_write');
        $ret_url = '';
        switch ($type) {
            case 1:
                $ret_url = $self_url.''.$short_url.'.html';
            break;

            case 2:
                $ret_url = $self_url.''.$short_url;
            break;

            case 3:
                if ($menu_folder) {
                    $ret_url = $self_url.''.$short_url.'/';
                }
                else {
                    $ret_url = $self_url.''.$short_url.'.html';
                }
            break;

            default:
                $ret_url = $self_url.''.$short_url.'.html';
        }
        $return_url = $ret_url;
    }
    else {
        //var_dump('not write');
        $return_url = $self_url.'?title='.$short_url;
    }

    return $return_url;
}


// 当前页面的URL
function get_self_url() {
    global $menu_folder;
    if (url_cfg_is_https()) {
        $base_dir = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    else {
        $base_dir = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        #var_dump($base_dir);
    }
    //var_dump($base_dir);
    if (strpos($base_dir, '?') !== false) {
        return dirname($base_dir)."/";
    }
    else if (strpos($base_dir, '.html') !== false) {
        return dirname($base_dir)."/";
    }
    else if (strpos($base_dir, '.php') !== false) {
        return dirname($base_dir)."/";
    }
    else if (str_ends_with($base_dir, '/')) {
        if ($menu_folder && is_write()) {
            $ret = dirname($base_dir)."/";
            if (strpos($ret, $_SERVER['HTTP_HOST']) !== false) {
                #var_dump($_SERVER['HTTP_HOST']);
                return dirname($base_dir)."/";
            }
            else {
                return $base_dir;
            }
        }
        else {
            return $base_dir;
        }
    }
    else {
        return $base_dir;
        //return dirname($base_dir)."/";
        //return $base_dir;
    }
}

# 以什么字符结尾
if (! function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle)
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}

// 是否HTTPS
function url_cfg_is_https() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}

// 域名短网址获取
function short_md5($keyword) {
    $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rand = $code[rand(0,25)]
        .strtoupper(dechex(date('m')))
        .date('d').substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',rand(0,99));
    for($a = md5($keyword, true ), $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV', $d = '', $f = 0; $f < 8; $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );

    return strtolower($d);
}

?>