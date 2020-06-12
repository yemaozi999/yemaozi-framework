<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/6/12
 * Time: 11:01
 */

namespace framework;


abstract class Facade
{
        protected static $alwaysNewInstance;

        /**
         * @desc 获取类名称
         * */
        protected abstract static function getFacadeClass();

        /**
         * 创建Facade实例
         * @static
         * @access protected
         * @param  string $class       类名或标识
         * @param  array  $args        变量
         * @param  bool   $newInstance 是否每次创建新的实例
         * @return object
         */
        protected static function createFacade(string $class = '', array $args = [], bool $newInstance = false)
        {
            $class = $class ?: static::class;
            $facadeClass = static::getFacadeClass();

            if ($facadeClass) {
                $class = $facadeClass;
            }

            if (static::$alwaysNewInstance) {
                $newInstance = true;
            }
            //使用容器获取类实例
            return Container::getInstance()->make($class, $args, $newInstance);
        }

        /**
         * @desc 调用实例方法
         * */
        public static function __callStatic($name, $arguments)
        {
            return call_user_func_array([static::createFacade(), $name], $arguments);
        }

        /**
         * @desc 主要用于实例传参
         * */
        public static function make(string $class = '', array $args = [], bool $newInstance = false){
            //如果当前类名(可能是父类或子类) 和 子类名不一致 , 直接使用子类的make, 调用静态方法体
            if (__CLASS__ != static::class) {
                return self::__callStatic('make', func_get_args());
            }

            if (true === $args) {
                // 总是创建新的实例化对象
                $newInstance = true;
                $args        = [];
            }

            return self::createFacade($class, $args, $newInstance);
        }

        /**
         * 带参数实例化当前Facade类 用于实体类的传参
         * @access public
         * @return object
         */
        public static function instance(...$args)
        {
            //如果当前类名(可能是父类或子类) 和 子类名不一致  返回子类的实例
            if (__CLASS__ != static::class) {
                return self::createFacade('', $args);
            }
        }

}