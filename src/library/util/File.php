<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 20:13
 */

namespace TiebaShow\util;


class File implements IFile
{
    public static function writeFIle($resContent, $strPath) {
        $bolResult = file_put_contents($strPath, $resContent);
        return $bolResult;
    }

    public static function readFile($strPath) {
        $strContent = file_get_contents($strPath);
        return $strContent;
    }
}