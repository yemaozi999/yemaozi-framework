<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/6
 * Time: 10:31
 */

use NoahBuscher\Macaw\Macaw;

Macaw::get("/",function(){
   echo "hello world";
});