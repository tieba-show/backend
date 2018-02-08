<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3
 * Time: 21:29
 */

namespace TiebaShow\exception;


class UserWasBannedException extends \Exception
{
    public function errorMessage() {
        return 'user was banned.';
    }
}