<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/6/5
 * Time: 14:47
 */
return [
    // session name
    'name'           => 'PHPSESSID',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => 'file',
    // 存储连接标识 当type使用cache的时候有效
    //'store'          => null,
    // 过期时间
    'expire'         => 86400,
    // 前缀
    'prefix'         => 'ymz',
    //序列化
    'serialize'=> ['json_encode', 'json_decode|true'],

];