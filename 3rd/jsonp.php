<?php



/**
 * jsonp转数组|Jsonp转json
 * @param string $jsonp jsonp字符串
 * @param bool $assoc  true转数组  false转对象
 * @return array|ArrayObject|null
 */
function jsonp_decode($jsonp, $assoc = false)
{
    $pattern = '/\((.*)\)/s';
    if (preg_match($pattern, $jsonp, $matches))
    {
        if (!empty($matches['1']))
        {
            return json_decode($matches['1'], $assoc);
        }
        return null;
    }
    return null;
}



?>