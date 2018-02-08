<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 20:10
 */

namespace TiebaShow\util;


class Network implements INetwork
{
    public static function httpGet($strUrl='')
    {
        return self::_getByPhp($strUrl);
    }

    private static function _getByCurl($strUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $strUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $strResponse = curl_exec($ch);
        curl_close($ch);
        return $strResponse;
    }

    private static function _getByPhp($strUrl)
    {
        echo "download: {$strUrl} \n";
        $strResponse = file_get_contents($strUrl);
        return $strResponse;
    }
}