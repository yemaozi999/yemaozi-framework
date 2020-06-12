<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/6/12
 * Time: 15:22
 */

namespace framework\facade;

use framework\Facade;

/**
 * @see \framework\Cache
 * @package framework\facade
 * @mixin \framework\Cache
 * */
class Cache extends Facade
{
    protected static function getFacadeClass()
    {
        return \framework\Cache::class;
    }

}