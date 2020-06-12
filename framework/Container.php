<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/21
 * Time: 10:00
 */

namespace framework;

use Psr\Container\ContainerInterface;
use Closure;
use think\helper\Str;

class Container implements ContainerInterface
{
    private $instances = [];

    protected static $instance;

    //禁止构造方法
    private function __construct()
    {
    }

    public static function getInstance():Container{
        if(is_null(static::$instance)){
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @desc 获取类名的实例
     * */
    public function get($key){

        if(!is_null($this->instances[$key])){
            $key = $this->instances[$key];
        }else{
            throw new Exception('class not found '.$key);
        }
    }

    public function has($key)
    {
        if(!is_null($this->instances[$key])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @desc 创建类实例
     * @param $class 类名称
     * @param vars 参数
     * @param newInstance  是否重新生成实例
     * */
    public function make($class,array $vars,bool $newInstance = false){
        if(isset($this->instances[$class])&&!$newInstance){
           return $this->instances[$class];
        }
        if($class instanceof Closure){
            //如果是闭包返回闭包方法
            $obj = $this->invokeFunction($class,$vars);
        }else{
            //如果是类返回类的实例
            $obj = $this->invokeClass($class,$vars);
        }
        $this->instances[$class] = $obj;

        return $obj;
    }

    /**
     * @desc 调用功能
     * @param function 闭包方法
     * @vars 参数数组
     * */
    private function invokeFunction($function, array $vars = []){
        try {
            $reflect = new \ReflectionFunction($function);
        } catch (\ReflectionException $e) {
            throw new \ReflectionException("function not exists: {$function}()");
        }

        $args = $this->bindParams($reflect, $vars);

        return $function(...$args);
    }

    /**
     * @desc 调用类
     * @param $class 类名称
     * @param vars 参数数组
     * */
    private function invokeClass(string $class,array $vars=[]){
        try {
            $reflect = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new \ReflectionException('class not found '.$class);
        }

        //检查是否有__make方法 有则返回
        if ($reflect->hasMethod('__make')) {
            $method = $reflect->getMethod('__make');
            if ($method->isPublic() && $method->isStatic()) {
                $args = $this->bindParams($method, $vars);
                return $method->invokeArgs(null, $args);
            }
        }
        //调用构造方法
        $constructor = $reflect->getConstructor();
        $args = $constructor ? $this->bindParams($constructor, $vars) : [];
        $object = $reflect->newInstanceArgs($args);

        return $object;
    }


    /**
     * 绑定参数
     * @access protected
     * @param ReflectionFunctionAbstract $reflect 反射类
     * @param array                      $vars    参数
     * @return array
     */
    protected function bindParams(\ReflectionFunctionAbstract $reflect, array $vars = []): array
    {
        if ($reflect->getNumberOfParameters() == 0) {
            return [];
        }

        // 判断数组类型 数字数组时按顺序绑定参数
        reset($vars);
        $type   = key($vars) === 0 ? 1 : 0;
        $params = $reflect->getParameters();
        $args   = [];

        foreach ($params as $param) {
            $name      = $param->getName();
            $lowerName = Str::snake($name);
            $class     = $param->getClass();

            if ($class) {
                $args[] = $this->getObjectParam($class->getName(), $vars);
            } elseif (1 == $type && !empty($vars)) {
                $args[] = array_shift($vars);
            } elseif (0 == $type && isset($vars[$name])) {
                $args[] = $vars[$name];
            } elseif (0 == $type && isset($vars[$lowerName])) {
                $args[] = $vars[$lowerName];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new \InvalidArgumentException('method param miss:' . $name);
            }
        }
        return $args;
    }

    /**
     * 获取对象类型的参数值
     * @access protected
     * @param string $className 类名
     * @param array  $vars      参数
     * @return mixed
     */
    protected function getObjectParam(string $className, array &$vars)
    {
        $array = $vars;
        $value = array_shift($array);

        if ($value instanceof $className) {
            $result = $value;
            array_shift($vars);
        } else {
            $result = $this->make($className);
        }

        return $result;
    }


}