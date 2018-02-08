<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 20:18
 */

namespace TiebaShow\spider;

//use TiebaShow\util\File;
use TiebaShow\util\Network;


abstract class BaseSpider implements ISpider
{
    public static function getMemberUsername($strForumName, $intStartPage, $intEndPage)
    {
        $arrMembers = [];
        $strPattern = '/<a href="\/home\/main\/\?un=(.+?)&fr=furank" target="_blank"/i';
        preg_match_all($strPattern, self::_getForumRank($strForumName, $intStartPage, $intEndPage), $arrMatchContent, PREG_SET_ORDER);
//        print_r($arrMatchContent);
        foreach ($arrMatchContent as $key => $value) {
            $strUsername = urldecode($value[1]);
//            echo "urldecode:".$strUsername."\n";
            $strUsername = iconv('GBK', 'UTF-8', $strUsername);
            $arrMembers[] = $strUsername;
        }
        return $arrMembers;
    }

    public static function getPortraitImage($strPortrait)
    {
        if (empty($strPortrait)) return null;
//        $strUrl = 'https://gss0.baidu.com/7Ls0a8Sm2Q5IlBGlnYG/sys/portraitl/item/'.$strPortrait;
        $strUrl = 'http://tb.himg.baidu.com/sys/portrait/item/'.$strPortrait;
        $resImage = Network::httpGet($strUrl);
        return $resImage;
    }

    private static function _getForumRank($strForumName, $intStartPage, $intEndPage)
    {
        $strResponse = '';
        for ($i=$intStartPage; $i < $intEndPage; $i++) {
            $strUrl = 'http://tieba.baidu.com/f/like/furank?ie=utf-8&kw='.$strForumName.'&pn='.$i;
            $strResponse .= Network::httpGet($strUrl);
        }
        return $strResponse;
    }
}