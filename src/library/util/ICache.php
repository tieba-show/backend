<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: ICache.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/7
 * Time: 14:30
 */

namespace TiebaShow\util;


interface ICache
{
    public function __construct($strHost, $intPort);
    public function store($strKey, $strValue, $intTimeout);
    public function load($strKey);
}