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

class Cache implements CacheInterface
{

    public static $instance;

    public $option = [

    ];

    private function __construct()
    {
    }

    public static function getInstance($option=[]):Driver{

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
            static::$instance = $class;
        }
        return static::$instance;
    }

    public static function __make():Driver{
        //获取默认配置
        $driver = Config::get('cache.default');
        static::$instance = new static();
        $option = Config::get('cache.stores.'.$driver);

        $container = Container::getInstance();

        switch(strtolower($driver)){
            case 'file':
                static::$instance = $container->make(File::class,[$option]);
                break;
            case 'redis':
                static::$instance = $container->make(Redis::class,[$option]);
                break;
            default:
                static::$instance = $container->make(File::class,[$option]);
                break;
        }
        return static::$instance;
    }


    public function has($key)
    {
        return $this->instance->has($key);
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->instance->getMultiple($keys,$default);
    }

    public function get($key, $default = null)
    {
        return $this->instance->get($key,$default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->instance->set($key,$value,$ttl);
    }

    public function delete($key)
    {
        return $this->instance->delete($key);
    }

    public function clear()
    {
        return $this->instance->clear();
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->instance->setMultiple($values,$ttl);
    }

    public function deleteMultiple($keys)
    {
        return $this->instance->deleteMultiple($keys);
    }


}