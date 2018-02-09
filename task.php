<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: task.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/4
 * Time: 13:57
 */

if (PHP_SAPI != 'cli') exit();

require_once 'autoload.php';
$arrConfig = require_once 'config.php';

use TiebaShow\spider\Spider;
use TiebaShow\util\Mongo;
use TiebaShow\util\File;
//use TiebaShow\config;
use TiebaShow\util\Queue;
use TiebaShow\image\Image;

/*$resMongo = new Mongo($arrConfig['database']['host'], $arrConfig['database']['port']);
$resMongo->selectDatabase($arrConfig['database']['database_name']);

$strForumTableName = $arrConfig['spider']['table_name']['forum'];
$strUserTableName = $arrConfig['spider']['table_name']['user'];
$cursor = $resMongo->selectOne($strUserTableName, ['data.name'=>'昌维001']);
var_dump($cursor);
exit();*/


$queue = new Queue();

while (1) {
    $arrTask = $queue->get();

    if (count($arrTask) > 0) {
        var_dump($arrTask);

        doTask($arrConfig, $arrTask);

        $queue->done([
            'forum_name' => $arrTask['forum_name'],
            'create_time' => $arrTask['create_time'],
        ]);
    } else {
        sleep($arrConfig['spider']['sleep_time']);
    }
}

//$strForumName = '昌维吧';
//$intStartPage = 1;
//$intEndPage = 2;

function doTask($arrConfig, $arrTask) {

    $strForumName = $arrTask['forum_name'];
    $intStartPage = $arrTask['start_page'];
    $intEndPage = $arrTask['end_page'];

    $resMongo = new Mongo($arrConfig['database']['host'], $arrConfig['database']['port']);
    $resMongo->selectDatabase($arrConfig['database']['database_name']);

    $arrUsername = Spider::getMemberUsername($strForumName, $intStartPage, $intEndPage);

    $strForumTableName = $arrConfig['spider']['table_name']['forum'];
    $strUserTableName = $arrConfig['spider']['table_name']['user'];

//    $arrUid = [];
    $arrPortrait = [];
    $arrImagePath = [];

    foreach ($arrUsername as $strUsername) {
        echo $strUsername."\n";
        $arrUserInfo = null;
        $cursor = $resMongo->selectOne($strUserTableName, ['raw_name'=>$strUsername]);
//        $cursor = $resMongo->selectOne($strUserTableName, ['data.name'=>$strUsername]);
//        var_dump($cursor);
        if ($cursor) {
            $arrUserInfo = $cursor;
        } else {
            $arrUserInfo = Spider::getUserInfoByUsername($strUsername);
            $resMongo->insert($strUserTableName, $arrUserInfo);
        }

//        $intUid = Spider::getUidByUserInfo($arrUserInfo);
//        $arrUid[] = $intUid;
        $strPortrait = Spider::getPortraitByUserInfo($arrUserInfo);
        // 部分超长id和被封禁的id无法获取到Portrait所以直接pass
        if (empty($strPortrait)) continue;
        $strPortraitWithoutQueryString = explode('?', $strPortrait)[0];
        $arrPortrait[] = $strPortraitWithoutQueryString;
        $resImage = Spider::getPortraitImage($strPortrait);
        $strPath = $arrConfig['spider']['path']['base_path'].'/'.$arrConfig['spider']['path']['portrait_save_path'].'/'.$strPortraitWithoutQueryString.'.jpg';
        File::writeFIle($resImage, $strPath);
//        TODO:路径不需要这里获取
        $arrImagePath[] = $strPath;
    }
    $arrForum = [
        'forum_name' => $strForumName,
        'users' => $arrPortrait,
    ];
    $resMongo->delete($strForumTableName, ['forum_name' => $strForumName]);
    $resMongo->insert($strForumTableName, $arrForum);
//    $strSavePath = Image::getSavePath($arrConfig, $arrTask, false);
//        TODO:这里修改一下逻辑
//    Image::sequenceMerge($strSavePath, $intOutputType, $arrImagePath, $intXNum, $intYNum);
}
