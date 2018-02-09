<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: index.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/4
 * Time: 14:18
 */

//error_reporting(0);

require_once 'autoload.php';
$arrConfig = require_once 'config.php';

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
//header("Access-Control-Allow-Headers: X-XSRF-TOKEN");
header("Access-Control-Allow-Methods: *");

use TiebaShow\util\Mongo;
use TiebaShow\util\Queue;
use TiebaShow\util\VerifyCode;
use TiebaShow\image\Image;

$strAction = $_REQUEST['action'];

$resMongo = new Mongo($arrConfig['database']['host'], $arrConfig['database']['port']);
$resMongo->selectDatabase($arrConfig['database']['database_name']);

switch ($strAction) {
    case 'add':
        if (!isset($_REQUEST['token']) || !isset($_REQUEST['verify_code']) || !VerifyCode::check($_REQUEST['token'], $_REQUEST['verify_code'])) {
            jsonReturn('verify code error', $_REQUEST);
            return;
        }
        if (!isset($_REQUEST['forum_name']) || !isset($_REQUEST['start_page']) || !isset($_REQUEST['end_page'])) {
            jsonReturn('submit param error', $_REQUEST);
            return;
        }

        $strForumName = $_REQUEST['forum_name'];
        $intStartPage = $_REQUEST['start_page'];
        $intEndPage = $_REQUEST['end_page'];
//        $intWidth = $_REQUEST['width'];
//        $intHeight = $_REQUEST['height'];
//        $intOutputType = $_REQUEST['output_type'];

        $strTaskTableName = $arrConfig['spider']['table_name']['task'];
        $arrTask = [
            'forum_name' => $strForumName,
            'start_page' => $intStartPage,
            'end_page' => $intEndPage,
//            'width' => $intWidth,
//            'height' => $intHeight,
//            'output_type' => $intOutputType,
        ];
//        $intRet = $resMongo->insert($strTableName, $arrTask);
        $queue = new Queue();
        $intRet = $queue->put($arrTask);
//        $intRet = $resMongo->insert($strTableName, $arrTask);
        if ($intRet) {
            jsonReturn('', $intRet);
        } else {
            jsonReturn('insert error', $intRet);
        }
        break;
    case 'list':
        $intPage = $_REQUEST['page'];
        $intPageSize = $_REQUEST['page_size'];
        $intLast = $_REQUEST['last'];

        $strTaskTableName = $arrConfig['spider']['table_name']['task'];
        $strForumTableName = $arrConfig['spider']['table_name']['forum'];
        $cursor = $resMongo->select($strTaskTableName, [])
            ->sort(['create_time' => Mongo::SORT_DESC])
            ->skip($intPageSize * ($intPage - 1))
            ->limit($intPageSize);
        $arrTask = [];
        foreach ($cursor as $arrRow) {
            $arrRow['page_number'] = $arrRow['end_page'] - $arrRow['start_page'];
            $cursorForum = $resMongo->selectOne($strForumTableName, ['forum_name'=>$arrRow['forum_name'] ]);
            $intImageNumber = count($cursorForum['users']);
            $arrRow['image_number'] = $intImageNumber;
//            $arrRow['image_number'] = $arrRow['page_number'] * 20;
            // 此代码必须在这里，不然会因为create_time字段已经被date函数格式化处理之后导致路径错误
//            $arrRow['output_path'] = Image::getSavePath($arrConfig, $arrRow, true);
            @$arrRow['spend_time'] = $arrRow['end_time'] - $arrRow['start_time'];
            @$arrRow['status'] = Queue::getStatus($arrRow['status']);
            @$arrRow['create_time'] = date('Y-m-d H:i', $arrRow['create_time']);
            if (isset($arrRow['start_time'])) $arrRow['start_time'] = date('Y-m-d H:i', $arrRow['start_time']);
            if (isset($arrRow['end_time'])) $arrRow['end_time'] = date('Y-m-d H:i', $arrRow['end_time']);
//            var_dump(Image::getSavePath($arrConfig, $arrRow, false));
            $arrTask[] = $arrRow;
        }
//        while($cursor->hasNext()) {
//            $arrTask[] = $cursor->getNext();
//        }
//        var_dump($arrTask);
        $arrRet = [
          'tasks' => $arrTask,
          'count' => $cursor->count(),
//          'last' => $arrTask.count(),
        ];
        jsonReturn('', $arrRet);
        break;
    case 'get_token':
        $strToken = VerifyCode::generate();
        jsonReturn('', $strToken);
        break;
    case 'verify_code':
        $strToken = $_REQUEST['token'];
        VerifyCode::showImage($strToken, true);
//        VerifyCode::showImage('test', false);
        break;
    case 'show_image':
        $strForumName = $_REQUEST['forum_name'];
        $strTaskTableName = $arrConfig['spider']['table_name']['forum'];
        $cursor = $resMongo->selectOne($strTaskTableName, ['forum_name'=>$strForumName]);

        $arrUid = $cursor['users'];

        $arrImagePath = [];
        foreach ($arrUid as $intUid) {
            $strPath = $arrConfig['spider']['path']['base_path'].'/'.$arrConfig['spider']['path']['portrait_save_path'].'/'.strval($intUid).'.jpg';
            $arrImagePath[] = $strPath;
        }

        $intOutputType = $_REQUEST['output_type'];
        $intXNum = $_REQUEST['width'];
        $intYNum = $_REQUEST['height'];
//        $intOutputType = 2;
//        $intXNum = 4;
//        $intYNum = 5;

//        var_dump($arrImagePath);

        Image::sequenceMerge(null, $intOutputType, $arrImagePath, $intXNum, $intYNum);
        break;
    default:
        break;
}

function jsonReturn($error='', $data='') {
//    var_dump($message);
    echo json_encode(['error' => $error, 'data' => $data]);
}
