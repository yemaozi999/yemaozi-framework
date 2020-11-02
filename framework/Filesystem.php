<?php


namespace framework;


use framework\core\filesystem\driver\Local;

class Filesystem
{
    public static $instance;

    public $option = [

    ];

    public static function getInstance($option=[]){

        if(static::$instance){
            return static::$instance;
        }else{
            //查看默认缓存驱动
            $driver = Config::get('filesystem.default');
            static::$instance = new static();
            $container = Container::getInstance();
            //获取驱动配置
            static::$instance->option = array_merge(Config::get('filesystem'),$option);

            if($driver=="local"){
                //保存本地文件
                $class = $container->make(Local::class,[Cache::getInstance(),static::$instance->option['disks'][$driver]]);
            }else{
                //保存七牛
                //$class = $container->make(Store::class,[Config::get('session.name'),new File(),Config::get('session.serialize')]);
            }

            static::$instance = $class;
        }
        return static::$instance;

    }

    public static function __make(){

        if(static::$instance){
            return static::$instance;
        }else{
            //查看默认缓存驱动
            $driver = Config::get('filesystem.default');
            static::$instance = new static();
            $container = Container::getInstance();
            //获取驱动配置
            //static::$instance->option = array_merge(Config::get('filesystem'),$option);
            static::$instance->option = Config::get("filesystem");

            if($driver=="local"){
                //保存本地文件
                $class = $container->make(Local::class,[Cache::getInstance(),static::$instance->option['disks'][$driver]]);
            }else{
                //保存七牛
                //$class = $container->make(Store::class,[Config::get('session.name'),new File(),Config::get('session.serialize')]);
            }
            static::$instance = $class;
        }
        return static::$instance;

    }


    public static function putFile(string $path, File $file, $rule = null, array $options = [])
    {
        return static::$instance->putFile($path, $file, $rule, $options);
    }

    public function putFileAs(string $path, File $file, string $name, array $options = [])
    {
        return static::$instance->putFileAs($path, $file, $name, $options);
    }

    public function __call($method, $parameters)
    {
        return static::$instance->filesystem->$method(...$parameters);
    }



}