<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/26
 * Time: 14:41
 */
namespace framework\dispatcher;

use framework\Config;

class Dispatcher implements BaseDispatcher
{
    private $dispatcher;

    private $app_name;

    private $controller_name;

    private $action_name;

    public function __construct($routes){

        $this->dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) use ($routes) {

            foreach($routes as $key=>$val){

                if(isset($val[2])){
                    $methods = explode('|',strtolower($val[2]));
                }else{
                    $methods = ['GET','POST','PUT','PATCH','DELETE'];
                }
                $r->addRoute($methods, $val[0],$val[1]);
            }
        });
    }

    public function run(){

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                header('HTTP/1.1 404 Not Found');
                header("status: 404 Not Found");
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                header('HTTP/1.1 405 Method Not Allowed');
                header("status: 405 Method Not Allowed");
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                //$vars = $routeInfo[2];

                $app_config = Config::get('app');

                $path_array = explode('/',trim($handler,"/"));

                $this->app_name = $path_array[0]??$app_config['default_app'];

                $this->controller_name = $path_array[1]??$app_config['default_controller'];

                $this->action_name = $path_array[2]??$app_config['default_action'];

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
                        header("status: 404 Not Found2");
                    }

                }else{
                    header('HTTP/1.1 404 Not Found');
                    header("status: 404 Not Found");
                }

                break;
        }

    }
}