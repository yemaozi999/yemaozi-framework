<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/14
 * Time: 11:06
 */

namespace app;


use eftec\bladeone\BladeOne;

class BaseController
{
    protected $request;

    protected $app_name;

    protected $controller_name;

    protected $action_name;

    protected $template;

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->app_name = APP_NAME;
        $this->controller_name = CONTROLLER_NAME;
        $this->action_name = ACTION_NAME;
    }

}