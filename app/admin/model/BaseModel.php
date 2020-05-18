<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/13
 * Time: 10:16
 */

namespace app\admin\model;


use framework\Config;
use think\facade\Db;
use think\Model;

class BaseModel extends Model
{
    public function __construct(array $data = [])
    {
        Db::setConfig(Config::get('database'));
        parent::__construct($data);
    }
}