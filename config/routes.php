<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/6
 * Time: 10:31
 */

use NoahBuscher\Macaw\Macaw;

Macaw::get('/', 'DemoController\demo@index');
Macaw::get('/page', 'DemoController\demo@page');
Macaw::get('/views/(:num)', 'DemoController\demo@views');

Macaw::dispatch();