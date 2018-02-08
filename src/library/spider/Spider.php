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
use TiebaShow\exception\UserWasBannedException;


class Spider extends BaseSpider implements ISpider
{
    public static function getUserInfoByUsername($strUsername)
    {
        $strUrl = 'http://tieba.baidu.com/home/get/panel?ie=utf-8&un='.$strUsername;
        $strResponse = Network::httpGet($strUrl);
        // print_r($strResponse);
        $arrUserInfo = json_decode($strResponse, true);
        return $arrUserInfo;
    }

    public static function getPortraitByUserInfo($arrUserInfo)
    {
        if (isset($arrUserInfo['data']['portrait'])) {
            $strPortrait = $arrUserInfo['data']['portrait'];
            return $strPortrait;
        }
//        throw new UserWasBannedException($arrUserInfo);
        return '';
    }

    public static function getUidByUserInfo($arrUserInfo)
    {
        if (isset($arrUserInfo['data']['id'])) {
            $intUid = intval($arrUserInfo['data']['id']);
            return $intUid;
        }
//        throw new UserWasBannedException($arrUserInfo);
        return -1;
    }

}