<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: Redis.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/7
 * Time: 14:32
 */

namespace TiebaShow\util;


class Redis implements ICache
{
    private $_resRedis;

    public function __construct($strHost, $intPort)
    {
//        parent::__construct($strHost, $intPort);
        $this->_resRedis = new \Redis();
        $this->_resRedis->connect($strHost, $intPort);
    }
    public function store($strKey, $strValue, $intTimeout)
    {
        return $this->_resRedis->set($strKey, $strValue, $intTimeout);
    }
    public function load($strKey)
    {
        return $this->_resRedis->get($strKey);
    }
}