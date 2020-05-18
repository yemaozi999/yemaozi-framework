<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/14
 * Time: 16:37
 */

namespace app\admin\controller;


use app\BaseController;
use eftec\bladeone\BladeOne;

class AdminBase extends BaseController
{
    protected $blade;

    protected $view_param;

        public function __construct()
        {
            $view = APP_PATH.APP_NAME."/view/";
            $cache = RUNTIME_PATH.APP_NAME."/tmp/";
            $this->blade = new BladeOne($view,$cache,BladeOne::MODE_AUTO);
            parent::__construct();
        }

    public function assign($name,$data){
            $this->view_param[$name] = $data;
        }

        public function fetch($action = ''){
            if($action){
                $action_name = $action;
            }else{
                $action_name = ACTION_NAME;
            }
            return $this->blade->run($this->controller_name.".".$action_name,$this->view_param);
        }
}