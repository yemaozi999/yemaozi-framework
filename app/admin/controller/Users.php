<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/8
 * Time: 0:58
 */
namespace app\admin\controller;

use app\admin\service\UserService;

class Users extends AdminBase
{
    public function index(){

        $server = new UserService();
        var_export($server->get_info());

        var_export($this->request);

        $this->assign('id',"43432");
        $this->assign('name','aaa');
        echo $this->fetch();
    }
}