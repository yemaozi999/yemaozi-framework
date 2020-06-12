<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/25
 * Time: 17:50
 */
namespace framework\core\session\driver;
use framework\Config;
use framework\core\session\Driver;
use think\helper\Arr;

class Cache extends Driver
{

    /** @var CacheInterface */
    protected $handler;

    /** @var integer */
    protected $expire;

    /** @var string */
    protected $prefix;

    public function __construct(array $config = [])
    {
        //获取当前缓存配置
        //获取过期时间
        //获取前缀
        if(empty($config)){
            $config = Config::get('session');
        }

        $cache_config = Config::get('cache.stores.'.$config['store']);
        $cache_config['expire'] = $config['expire'];
        $cache_config['prefix'] = $config['prefix'];

        $this->handler = \framework\Cache::getInstance($cache_config);

        $this->expire  = Arr::get($cache_config, 'expire', 1440);
        $this->prefix  = Arr::get($cache_config, 'prefix', '');
    }

    public function destroy($session_id)
    {
        return (string) $this->handler->delete($this->prefix . $session_id);
    }

    public function read($session_id)
    {
        return (string) $this->handler->get($this->prefix . $session_id);
    }

    public function write($session_id, $session_data)
    {
        return $this->handler->set($this->prefix . $session_id, $session_data, $this->expire);
    }

}