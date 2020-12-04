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
use think\facade\Db;

class App
{

    private $route;
    private $dispatcher;

    protected static $instance;

    protected $session;

    protected $request;

    protected $response;

    public function __construct()
    {
        //common.php 文件引入
        //include_once PRO_ROOT."app/common.php";

        //读取extend文件夹的文件
        $this->extendLoad(EXTEND_PATH);

        $auto_route = Config::get('app.auto_route');
        Db::setConfig(Config::get('database'));
        if(!self::$instance){

            $request = new Request();
            $this->request = $request->__make();

            if(Config::get("session")){
                $varSessionId = Config::get('session.var_session_id');
                //$this->session = new Session($this);
                //$this->session = Session::getInstance();
                Session::getInstance();

                $cookieName   = Session::getName();

                if ($varSessionId && $this->request->request($varSessionId)) {
                    $sessionId = $this->request->request($varSessionId);
                } else {
                    $sessionId = $this->request->cookie($cookieName);
                }

                if ($sessionId) {
                    Session::setId($sessionId);
                }
                Session::init();
                //$request->withSession($this->session);
                setcookie($cookieName, Session::getId());
            }

            if($auto_route == false){
                $this->route = Config::get('route.path');
                $this->dispatcher = new Dispatcher($this->route);
            }else{
                $this->dispatcher = new AutoDispatcher();
            }
            self::$instance = $this;
        }else{
            return self::$instance;
        }
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function run(){
        $this->dispatcher->run();
    }

    private static function extendLoad($dir, $level=0) {

        $temp=scandir($dir);
        $level++;
        //遍历文件夹
        foreach($temp as $v){
            $a=$dir.'/'.$v;
            if(is_dir($a)){//如果是文件夹则执行
                if($v=='.' || $v=='..'){
                    continue;
                }
                static::extendLoad($a, $level);
            }else{
                if(is_file($a)){
                    require $a;
                }
            }
        }
    }

}