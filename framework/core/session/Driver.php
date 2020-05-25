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
    public function destroy($session_id){}

    public function read($session_id){}

    public function write($session_id, $session_data){}
}