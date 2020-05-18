<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/13
 * Time: 9:35
 */
return [
    // 默认数据连接标识
    'default'     => 'mysql',
    // 数据库连接信息
    'connections' => [
        'mysql' => [
            // 数据库类型
            'type'     => 'mysql',
            // 主机地址
            'hostname' => '127.0.0.1',
            // 用户名
            'username' => 'root',
            // 数据库密码
            'password'    => 'root',
            // 数据库连接端口
            'hostport'    => '3306',
            //数据库名
            'database' => 'yemaozi',
            // 数据库编码默认采用utf8
            'charset'  => 'utf8',
            // 数据库表前缀
            'prefix'   => 'fm_',
            // 数据库调试模式
            'debug'    => true,
        ],
    ],
];