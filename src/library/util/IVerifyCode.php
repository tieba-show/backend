<?php
/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: IVerifyCode.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/6
 * Time: 23:46
 */

namespace TiebaShow\util;


interface IVerifyCode
{
    public static function generate();
    public static function check($strToken, $strInputCode);
    public static function showImage($strValue, $bolIsToken);
}