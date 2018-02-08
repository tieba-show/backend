<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 20:19
 */

namespace TiebaShow\spider;


interface ISpider
{
    /**
     * 获取贴吧会员ID数组
     * @param $strForumName
     * @param $intStartPage
     * @param $intEndPage
     * @return array
     */
    public static function getMemberUsername($strForumName, $intStartPage, $intEndPage);

    /**
     * @param $strUsername
     * @return array
     */
    public static function getUserInfoByUsername($strUsername);

    /**
     * 通过用户名获取贴吧Portrait（加密头像字符串）
     * @param $strForumName
     * @param $intStartPage
     * @param $intEndPage
     * @return string
     */
    public static function getPortraitByUserInfo($arrUserInfo);

    /**
     * 通过用户信息获取用户Uid
     * @param $arrUserInfo
     * @return int
     */
    public static function getUidByUserInfo($arrUserInfo);

    /**
     * 通过Portrait获取实际头像资源
     * @param $strPortrait
     * @return resource
     */
    public static function getPortraitImage($strPortrait);
}