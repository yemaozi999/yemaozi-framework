<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/26
 * Time: 15:04
 */

return [
    'path'=>[
        ['/','/index/index','GET'],
        ['/user/{id:\d+}','/user/user','GET|POST'],
        ['/aa/{id:\d+}','/aa/aa']
    ]
];