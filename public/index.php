<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/4/8
 * Time: 0:25
 */
namespace framework;

require_once "../vendor/autoload.php";

require_once './slog.php';

define("PRO_ROOT",__DIR__."/../");
define("VENDOR_PATH",__DIR__."/../vendor/");
define("CONFIG_PATH",__DIR__."/../config/");
define("APP_PATH",__DIR__."/../app/");
define("FRAMEWORK_PATH",__DIR__.'/../framework/');
define("RUNTIME_PATH",__DIR__."/../runtime/");
define("EXTEND_PATH",__DIR__."/../extend/");

//require_once FRAMEWORK_PATH."ClassLoader/ClassLoader.php";

$app = new App();
$app->run();

