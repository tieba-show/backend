<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/4
 * Time: 11:40
 */

namespace TiebaShow\util;


interface IQueue
{
    public function put($arrTask);
    public function get();
    public function done($arrTask);
    public function size();
}