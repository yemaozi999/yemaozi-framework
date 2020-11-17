<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/13
 * Time: 9:35
 */
return [
    'default' =>  'qiniu',
    'prefix'  => 'filesystem',//前缀
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root'   => RUNTIME_PATH . 'storage',
        ],
        'public' => [
            'type'     => 'local',
            'root'       => PUBLIC_PATH . 'storage',
            'url'        => '/storage',
            'visibility' => 'public',
        ],
        'qiniu' => [
            'accessKey' => '',
            'secretKey' => '',
            'bucket' => '',
            'domain' =>'',
            'url'=>''
        ],


    ],
];