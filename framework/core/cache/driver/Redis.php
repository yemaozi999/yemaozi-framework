<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/19
 * Time: 16:21
 */
namespace framework\core\cache\driver;

use framework\core\cache\Driver;
use Predis\Client;

class Redis extends Driver
{
    protected $options = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
        'tag_prefix' => 'tag:',
        'serialize'  => [],
    ];

    private $handler;

    public function __construct($options)
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        if(extension_loaded('redis')){

            $this->handler = new \Redis();
            if($this->options['persistent']){
                $this->handler->pconnect($this->options['host'],$this->options['port'],$this->options['timeout'],'persistent_id_' . $this->options['select']);
            }else{
                $this->handler->connect($this->options['host'],$this->options['port'],$this->options['timeout']);
            }

        }elseif(class_exists('\Predis\Client')){
            $params = [];
            foreach ($this->options as $key => $val) {
                if (in_array($key, ['aggregate', 'cluster', 'connections', 'exceptions', 'prefix', 'profile', 'replication', 'parameters'])) {
                    $params[$key] = $val;
                    unset($this->options[$key]);
                }
            }

            if ('' == $this->options['password']) {
                unset($this->options['password']);
            }

            $this->handler = new \Predis\Client($this->options, $params);

            $this->options['prefix'] = '';
        }else{
            throw new \BadFunctionCallException('not support: redis');
        }

        if (0 != $this->options['select']) {
            $this->handler->select($this->options['select']);
        }

    }

    public function get($key, $default = null)
    {
        $value = $this->handler->get($this->getCacheKey($key));

        if (false === $value || is_null($value)) {
            return $default;
        }
        return $this->unserialize($value);
    }

    public function set($key, $value, $expire = null)
    {
        if ($expire==null){
            $expire = $this->options['expire'];
        }else{
            $expire = $this->getExpireTime($expire);
        }

        if(!$expire){
            $this->handler->set($this->getCacheKey($key),$this->serialize($value));
        }else{
            $this->handler->setex($this->getCacheKey($key),$expire,$this->serialize($value));
        }
        return true;
    }

    public function setMultiple($values, $expire = null)
    {
        foreach ($values as $key => $val) {
            $result = $this->set($key, $val, $expire);

            if (false === $result) {
                return false;
            }
        }
        return true;
    }

    public function clear()
    {
        $this->handler->flushdb();
        return true;
    }
    public function delete($key)
    {
        $result = $this->handler->del($this->getCacheKey($key));
        return $result > 0;
    }
    public function deleteMultiple($keys)
    {
        foreach($keys as $key){
            $result = $this->delete($key);
            if (false === $result) {
                return false;
            }
        }
        return true;
    }
    public function getMultiple($keys, $default = null)
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }
    public function has($key)
    {
        if($this->handler->exists($this->getCacheKey($key))){
            return true;
        }
        return false;
    }
}