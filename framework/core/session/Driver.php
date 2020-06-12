<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/25
 * Time: 18:05
 */

namespace framework\core\session;

abstract class Driver
{
    public abstract function destroy($session_id);

    public abstract function read($session_id);

    public abstract function write($session_id, $session_data);
}