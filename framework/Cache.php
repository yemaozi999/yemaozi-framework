<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/22
 * Time: 14:34
 */

namespace framework;

use framework\core\cache\Driver;
use framework\core\cache\driver\File;
use framework\core\cache\driver\Redis;
use Psr\SimpleCache\CacheInterface;

class Cache //implements CacheInterface
{

    public static $instance;

    public $driver;

    public $option = [

    ];

    private function __construct()
    {
    }

    public static function getInstance($option=[]){

        if(static::$instance){
            return static::$instance;
        }else{
            //查看默认缓存驱动
            $driver = Config::get('cache.default');
            static::$instance = new static();
            $container = Container::getInstance();
            //获取驱动配置
            switch(strtolower($driver)){
                case 'file':
                    static::$instance->option = array_merge(Config::get('cache.stores.file'),$option);
                    $class = $container->make(File::class,[static::$instance->option]);
                    break;
                case "redis":
                    static::$instance->option = array_merge(Config::get('cache.stores.redis'),$option);
                    $class = $container->make(Redis::class,[static::$instance->option]);
                    break;
                default:
                    static::$instance->option = array_merge(Config::get('cache.stores.file'),$option);
                    $class = $container->make(File::class,[static::$instance->option]);
                    break;
            }
            static::$instance->driver = $class;
        }
        return static::$instance;
    }

    public static function __make(){
        //获取默认配置
        $driver = Config::get('cache.default');
        static::$instance = new static();
        $option = Config::get('cache.stores.'.$driver);

        $container = Container::getInstance();

        switch(strtolower($driver)){
            /*case 'file':
                $class = $container->make(File::class,[$option]);
                break;*/
            case 'redis':
                $class = $container->make(Redis::class,[$option]);
                break;
            default:
                $class = $container->make(File::class,[$option]);
                break;
        }
        static::$instance->driver = $class;

        return static::$instance;
    }


    public function has($key)
    {
        return static::$instance->driver->has($key);
    }

    public function getMultiple($keys, $default = null)
    {
        return static::$instance->driver->getMultiple($keys,$default);
    }

    public function get($key, $default = null)
    {
        return static::$instance->driver->get($key,$default);
    }

    public function set($key, $value, $ttl = null)
    {
        return static::$instance->driver->set($key,$value,$ttl);
    }

    public function delete($key)
    {
        return static::$instance->driver->delete($key);
    }

    public function clear()
    {
        return static::$instance->driver->clear();
    }

    public function setMultiple($values, $ttl = null)
    {
        return static::$instance->driver->setMultiple($values,$ttl);
    }

    public function deleteMultiple($keys)
    {
        return static::$instance->driver->deleteMultiple($keys);
    }



}