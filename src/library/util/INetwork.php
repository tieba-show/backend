<?php
/**
 * Created by PhpStorm.
 * User: cw1997
 * Date: 2018/2/3
 * Time: 20:01
 */

namespace TiebaShow\util;


interface INetwork
{
    /**
     * @param $strUrl
     * @return string
     */
    public static function httpGet($strUrl);
}