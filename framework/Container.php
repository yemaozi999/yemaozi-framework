<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/21
 * Time: 10:00
 */

namespace framework;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private $instances = [];

    protected static $instance;

    //禁止构造方法
    private function __construct()
    {
    }

    public static function getInstance():Container{
        if(is_null(static::$instance)){
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function get($key){

        if(!is_null($this->instances[$key])){
            $key = $this->instances[$key];
        }

        $reflect = new \ReflectionClass($key);

        //获取构造函数
        $c = $reflect->getConstructor();

        if(!$c){
            return new $key;
        }

        //看看构造函数是否有参数
        $params = $c->getParameters();

        if(empty($params)){
            return new $key;
        }

        foreach ($params as $key=>$val){
            $class = $val->getClass();
            if(!$class){
                continue;
            }else{
                $args[] = $this->get($class->name);
            }
        }
        return $reflect->newInstanceArgs($args);

    }

    public function has($key)
    {
        if(!is_null($this->instances[$key])){
            return true;
        }else{
            return false;
        }
    }


}