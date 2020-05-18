<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/7
 * Time: 15:10
 */

class ClassLoader
{
    public static function base_loader($name){
        $file = PRO_ROOT.$name.".php";
        if(file_exists($file)){
            require_once $file;
        }
    }
}

spl_autoload_register("ClassLoader::base_loader");