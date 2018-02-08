<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/4
 * Time: 12:42
 */

namespace TiebaShow\config;

return [
    'database' => [
        'host' => '127.0.0.1',
        'port' => '27017',
        'database_name' => 'tiebashow',
    ],
    'spider' => [
        'table_name' => [
            'user' => 'user',
            'task' => 'task',
            'forum' => 'forum',
        ],
        'path' => [
            'base_path' => 'D:/www/tieba-show',
            'portrait_save_path' => 'img/portrait',
            'output_save_path' => 'img/output',
        ],
        'sleep_time' => 10,
    ],
    'image' => [],
];

/*class Config
{

    const strDatabaseHost = '127.0.0.1';
    const intDatabasePort = '27017';

    const intDatabasePort = '27017';
    const intDatabasePort = '27017';
}*/