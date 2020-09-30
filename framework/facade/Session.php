<?php
/**
 * Created by PhpStorm.
 * User: yemao
 * Date: 2020/9/25
 * Time: 15:22
 */

namespace framework\facade;

use framework\Facade;

/**
 * @see \framework\Session
 * @package framework\facade
 * @mixin \framework\Session
 * */
class Session extends Facade
{
    protected static function getFacadeClass()
    {
        return \framework\Session::class;
    }

}