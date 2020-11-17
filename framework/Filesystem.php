<?php


namespace framework;


use framework\core\filesystem\driver\Local;
use framework\core\filesystem\driver\Qiniu;

class Filesystem
{
    public static $instance;

    public $driver;

    public $option = [

    ];

    public static function __make(){

        if(static::$instance){
            return static::$instance;
        }else{
            //查看默认缓存驱动
            $driver = Config::get('filesystem.default');
            static::$instance = new static();
            $container = Container::getInstance();
            //获取驱动配置
            static::$instance->option = Config::get("filesystem");

            switch($driver){
                case "qiniu":
                    $class = $container->make(Qiniu::class,[Cache::getInstance(),static::$instance->option['disks'][$driver]]);
                    break;
                case "local":
                    $class = $container->make(Local::class,[Cache::getInstance(),static::$instance->option['disks'][$driver]]);
                    break;
                default:
                    $class = $container->make(Local::class,[Cache::getInstance(),static::$instance->option['disks']["public"]]);
                    break;
            }

            static::$instance->driver = $class;
        }
        return static::$instance;
    }


    public static function putFile(string $path, File $file, $rule = null, array $options = [])
    {
        return static::$instance->driver->putFile($path, $file, $rule, $options);
    }

    public static function putFileAs(string $path, File $file, string $name, array $options = [])
    {
        return static::$instance->driver->putFileAs($path, $file, $name, $options);
    }

    /**
     * 单独使用方法
     * example Filesystem::disk("local")->putFile('path',$file,'md5')
     * */
    public static function disk($driver){

        $option = Config::get("filesystem");
        switch($driver){
            case "qiniu":
                $class = new Qiniu(Cache::getInstance(),$option['disks'][$driver]);
                break;
            case "local":
                $class = new Local(Cache::getInstance(),$option['disks'][$driver]);
                break;
            default:
                $class = new Local(Cache::getInstance(),$option['disks']["public"]);
                break;
        }
        return $class;
    }

}