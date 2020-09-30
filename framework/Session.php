<?php
/**
 * Created by PhpStorm.
 * User: yemao
 * Date: 2020/9/25
 * Time: 15:37
 */

namespace framework;

use framework\core\session\Driver;
use framework\core\session\driver\File;
use framework\core\session\Store;
use think\helper\Arr;

/**
 * Session管理类
 * @package think
 * @mixin Store
 */
class Session
{

    public static $instance;

    public $option = [

    ];

    public static function getInstance($option=[]):Store{

        if(static::$instance){
            return static::$instance;
        }else{
            //查看默认缓存驱动
            $driver = Config::get('session.type');
            static::$instance = new static();
            $container = Container::getInstance();
            //获取驱动配置
            static::$instance->option = array_merge(Config::get('session'),$option);

            if($driver=="cache"){
                $class = new Store(Config::get('session.name'),new \framework\core\session\driver\Cache(),Config::get('session.serialize'));
                //$class = $container->make(Store::class,[Config::get('session.name'),\framework\core\session\driver\Cache::class,Config::get('session.serialize')]);
            }else{
                $class = new Store(Config::get('session.name'),new File(),Config::get('session.serialize'));
                //$class = $container->make(Store::class,[Config::get('session.name'),File::class,Config::get('session.serialize')]);
            }

            static::$instance = $class;
        }
        return static::$instance;

    }

    public static function __make():Store{
        //获取默认配置

        if(static::$instance) {
            return static::$instance;
        }else{
            $driver = Config::get('session.type');
            static::$instance = new static();
            $option = Config::get('session');
            $container = Container::getInstance();
            if($driver=="cache"){
                static::$instance = new Store(Config::get('session.name'),new \framework\core\session\driver\Cache(),Config::get('session.serialize'));
                //static::$instance = $container->make(Store::class,[Config::get('session.name'),new \framework\core\session\driver\Cache(),Config::get('session.serialize')]);
            }else{
                static::$instance = new Store(Config::get('session.name'),new File(),Config::get('session.serialize'));
                //static::$instance = $container->make(Store::class,[Config::get('session.name'),new File(),Config::get('session.serialize')]);
            }
            return static::$instance;
        }
    }


    public static function get($key, $default = null)
    {
        return static::$instance->get($key,$default);
    }

    public static function set($key, $value)
    {
        return static::$instance->set($key,$value);
    }

    public static function save()
    {
        return static::$instance->save();
    }

    public static function init(){
        static::$instance->init();
    }

    public static function setId($sessionId){
        return static::$instance->setId($sessionId);
    }

    public static function getName(){
        return static::$instance->getName();
    }

    public static function getId(){
        return static::$instance->getId();
    }


}