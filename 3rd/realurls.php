
<?php
 
/*
    @param str $url 查询
    $return str  定向后的url的真实url
 */
function getrealurl($url) {

    stream_context_set_default( [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $header = get_headers($url, 1);
    if (strpos($header[0],'301') || strpos($header[0],'302')) {
        if(is_array($header['Location'])) {
            return $header['Location'][count($header['Location'])-1];
        }else{
            return $header['Location'];
        }
    }else {
        return $url;
    }
}

#$url = 'http://google.com';
#$url = getrealurl($url);
#echo '真实的url为：'.$url;
//真实的url为：http://www.google.com.hk/

?>
