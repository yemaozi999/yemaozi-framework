<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/29
 * Time: 14:13
 */

namespace framework\dispatcher;

use framework\Config;

class AutoDispatcher implements BaseDispatcher
{
        private $app_name;
        private $controller_name;
        private $action_name;

        public function __construct(){

            if (!isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])){
                $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
            }
            $path_info = $_SERVER['PATH_INFO'];
            $app_config = Config::get('app');
            if($path_info=='index.php'){
                $path_info=$app_config['default_app'];
            }
            $path_exp = explode(".",$path_info);
            $path = array_shift($path_exp);
            $path_array = explode('/',trim($path,"/"));
            $this->app_name = $path_array[0]??$app_config['default_app'];
            $this->controller_name = $path_array[1]??$app_config['default_controller'];
            $this->action_name = $path_array[2]??$app_config['default_action'];

        }

        public function run(){
            /*$uri = $_SERVER['REQUEST_URI'];
            if (false !== $pos = strpos($uri, '?')) {
                $uri = substr($uri, 0, $pos);
            }
            $uri = rawurldecode($uri);*/
            $class_mix = 'app\\'.$this->app_name.'\controller'.'\\'.$this->controller_name;
            if(class_exists($class_mix)){
                define("APP_NAME",$this->app_name);
                define("CONTROLLER_NAME",$this->controller_name);
                define('ACTION_NAME',$this->action_name);
                $class = new $class_mix();
                if(method_exists($class,$this->action_name)){
                    call_user_func([$class,$this->action_name]);
                }else{
                    header('HTTP/1.1 404 Not Found');
                    header("status: 404 Not Found");
                }
            }else{
                header('HTTP/1.1 404 Not Found');
                header("status: 404 Not Found");
            }
        }
}