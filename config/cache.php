<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/19
 * Time: 14:20
 */
return [
    'default'    =>    'file',
    'stores'    =>    [
        // 文件缓存
        'file'   =>  [
            // 驱动方式
            'type'   => 'file',
            // 设置不同的缓存保存目录
            'path'   => '../runtime/file/',
            'prefix'     => 'test_',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
        ],
        // redis缓存
        'redis'   =>  [
            // 驱动方式
            'type'   => 'redis',
            // 服务器地址
            'host'       => '127.0.0.1',
            'port'      => 6379,
            'prefix'     => 'test_',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
        ],
    ],
];