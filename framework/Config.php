<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/26
 * Time: 15:32
 */

namespace framework;


class Config
{
    public function __construct(){

    }

    /**
     * @desc 获取配置参数
     * @example
     */
    public static function get($name="",$default="",$app=""){

        $config_path = CONFIG_PATH;
        if($app){
            $config_path .= $app."/";
        }

        $config_param = explode(".",$name);

        $file_name = array_shift($config_param);
        $file_name = $file_name.".php";

        if(file_exists($config_path.$file_name)){
            $config = include($config_path.$file_name);

            $result = self::get_config_content($config,$config_param);
            if($result){
                return $result;
            }else{
                return $default;
            }

        }else{
            return $default;
        }
    }

    private static function get_config_content($data,$array=[]){

        if(count($array)>0){
            $key=array_shift($array);
            $data = $data[$key];
            if($data==null){
                return false;
            }
            $result = self::get_config_content($data,$array);
        }else{
            $result = $data;
        }

        return $result;

    }

}