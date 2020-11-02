<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/13
 * Time: 9:35
 */
return [
    'default' =>  'local',
    'prefix'  => 'filesystem',//前缀
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root'   => RUNTIME_PATH . 'storage',
        ],
        'public' => [
            'type'     => 'local',
            'root'       => PUBLIC_PATH . 'public/storage',
            'url'        => '/storage',
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
    ],
];