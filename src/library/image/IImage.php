<?php

/**
 * Copyright 2018 cw1997. All Rights Reserved.
 * Project: tieba-show
 * File: IImage.php
 * Author: cw1997 [867597730@qq.com]
 * Website: changwei.me
 * Repo: https://github/cw1997
 * Date: 2018/2/5
 * Time: 0:19
 */


namespace TiebaShow\image;

interface IImage
{
    /**
     * 输入Uid数组，从硬盘获取头像集合，拼接之后输出图像
     * @param $strSavePath
     * @param $arrUid
     * @param $intXNum
     * @param $intYNum
     * @return mixed
     */
    public static function sequenceMerge($strSavePath, $intOutputType, $arrImagePath, $intXNum, $intYNum);
}