<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 21:01
 */

namespace TiebaShow\util;


class Mongo implements IDatabase
{
    private $_strIp;
    private $_intPort;
    private $_resClient;
    private $_resDatabase;

    const SORT_ASC = 1;
    const SORT_DESC = -1;

    public function __construct($strIp='127.0.0.1', $intPort=27017) {
        $this->_strIp = $strIp;
        $this->_intPort = $intPort;
        $this->_connect();
    }

    public function selectDatabase($strDatabaseName) {
        return $this->_resDatabase = $this->_resClient->$strDatabaseName;
//        $this->_resDatabase = $this->_resClient->selectDB($strDatabaseName);
    }

    public function select($strTableName, $arrCondition) {
        return $this->_resDatabase->$strTableName->find($arrCondition);
    }

    public function selectOne($strTableName, $arrCondition) {
        return $this->_resDatabase->$strTableName->findOne($arrCondition);
    }

    public function insert($strTableName, $arrData) {
        return $this->_resDatabase->$strTableName->insert($arrData);
    }

    public function update($strTableName, $arrOldData, $arrNewData) {
        return $this->_resDatabase->$strTableName->update($arrOldData, $arrNewData);
    }

    public function updateByField($strTableName, $arrOldData, $arrNewData) {
        return $this->_resDatabase->$strTableName->update($arrOldData, ['$set' => $arrNewData]);
    }

    public function delete($strTableName, $arrCondition) {
        return $this->_resDatabase->$strTableName->remove($arrCondition);
    }

    public function _connect() {
        $strServer = 'mongodb://'.$this->_strIp.':'.strval($this->_intPort);
        $this->_resClient = new \MongoClient($strServer); // 连接默认主机和端口为：mongodb://localhost:27017
    }
}