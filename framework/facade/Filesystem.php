<?php

namespace framework\facade;

use framework\Facade;

/**
 * @see \framework\Filesystem
 * @package framework\facade
 * @mixin \framework\Filesystem
 * */
class Filesystem extends Facade
{
    protected static function getFacadeClass()
    {
        return \framework\Filesystem::class;
    }
}