<?php
/**
 * Created by PhpStorm.
 * User: cw1997
 * Date: 2018/2/3
 * Time: 20:01
 */

namespace TiebaShow\util;


interface IFile
{
    /**
     * @param $resContent
     * @param $strPath
     * @return bool
     */
    public static function writeFile($resContent, $strPath);

    /**
     * @param $strPath
     * @return resource
     */
    public static function readFile($strPath);
}