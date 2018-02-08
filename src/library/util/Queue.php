<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/4
 * Time: 11:43
 */

namespace TiebaShow\util;


class Queue implements IQueue
{
    private $resMongo;
    private $_strTableName;

    const STATUS_UNDEFINED = 0;
    const STATUS_UNPROCESSED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_PROCESSED = 3;
    const STATUS_PROCESS_ERROR = 4;

    static $arrStatus = [
        '未定义',
        '等待处理',
        '正在处理',
        '处理完毕',
        '任务出错',
    ];

    function __construct()
    {
        $this->resMongo = new Mongo();
        $this->resMongo->selectDatabase('tiebashow');
        $this->_strTableName = 'task';
    }

    public static function getStatus($intStatus)
    {
        return self::$arrStatus[$intStatus];
    }

    public function put($arrTask)
    {
        $arrTask['create_time'] = time();
        $arrTask['status'] = self::STATUS_UNPROCESSED;
        return $this->resMongo->insert($this->_strTableName, $arrTask);
    }

    public function done($arrCondition)
    {
        $arrTask = $this->resMongo->selectOne($this->_strTableName, $arrCondition);
        if (!isset($arrTask)) {
            return [];
//            throw
        }
        $arrNewTask = $arrOldTask = $arrTask;
        $arrNewTask['end_time'] = time();
        $arrNewTask['status'] = self::STATUS_PROCESSED;
        $this->resMongo->update($this->_strTableName, $arrOldTask, $arrNewTask);
    }

    public function get()
    {
        $cursor = $this->resMongo->select($this->_strTableName,
                ['status' => self::STATUS_UNPROCESSED])->sort(['create_time' => Mongo::SORT_ASC]);
//        if (!$arrTask->hasNext()) {
//            return [];
////            throw
//        }
//        $arrNewTask = $arrOldTask = $arrTask->getNext();
        $arrNewTask = $arrOldTask = [];
        foreach ($cursor as $arrTask) {
            $arrOldTask = $arrTask;
            $arrNewTask = $arrOldTask;
            break;
        }
//        $arrNewTask = $arrOldTask = $arrTask->next();
        if ($arrNewTask == null) return [];
        $arrNewTask['start_time'] = time();
        $arrNewTask['status'] = self::STATUS_PROCESSING;
        $this->resMongo->updateByField($this->_strTableName, $arrOldTask, $arrNewTask);
        return $arrNewTask;
    }

    public function size()
    {
        return $arrOldTask = $this->resMongo->select($this->_strTableName, [])->count();
    }
}