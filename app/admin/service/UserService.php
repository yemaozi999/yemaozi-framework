<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/13
 * Time: 10:12
 */

namespace app\admin\service;


use app\admin\model\Users;

class UserService
{
    public function get_info(){
        return Users::where([])->select()->toArray();
    }
}