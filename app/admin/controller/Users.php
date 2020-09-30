<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/8
 * Time: 0:58
 */
namespace app\admin\controller;

use app\admin\service\UserService;
use eftec\bladeone\BladeOne;
use framework\App;
use framework\facade\Cache;
use framework\facade\Session;
use framework\Request;

class Users extends AdminBase
{
    public function index(){

        $server = new UserService();

        $param = $this->request->param();

        $this->assign('id',"43432");
        $this->assign('name','aaa');

        $this->assign('data',[1,2,3,4,5,6]);

        echo $this->fetch();
    }

    public function cache(){
/*        Cache::getInstance()->set('name','100');

        echo Cache::getInstance()->get('name','0');*/

        //Cache::set('name','1000');
        echo Cache::get("name");

    }

    public function session(){
        //Session::set("name",321);
        echo Session::get("name");
    }
}