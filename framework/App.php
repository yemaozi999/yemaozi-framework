<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/26
 * Time: 17:49
 */

namespace framework;


use framework\dispatcher\AutoDispatcher;
use framework\dispatcher\Dispatcher;

class App
{

    private $route;
    private $dispatcher;

    public function __construct()
    {
        //common.php 文件引入
        include_once PRO_ROOT."app/common.php";

        $auto_route = Config::get('app.auto_route');

        if($auto_route == false){
            $this->route = Config::get('route.path');
            $this->dispatcher = new Dispatcher($this->route);
        }else{
            $this->dispatcher = new AutoDispatcher();
        }

    }

    public function run(){
        $this->dispatcher->run();
    }
}