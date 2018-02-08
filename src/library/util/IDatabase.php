<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 20:55
 */

namespace TiebaShow\util;


interface IDatabase
{

    public function __construct($strIp, $intPort);

//    public function connect();

    public function selectDatabase($strDatabaseName);

    public function select($strTableName, $arrCondition);

    public function insert($strTableName, $arrData);

    public function update($strTableName, $arrOldData, $arrNewData);

    public function delete($strTableName, $arrCondition);
}