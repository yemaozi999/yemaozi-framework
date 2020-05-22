<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/19
 * Time: 16:20
 */
namespace framework\core\cache\driver;

use framework\core\cache\Driver;

class File extends Driver
{
    protected $options = [
        'expire'        => 0,
        'cache_subdir'  => true,
        'prefix'        => '',
        'path'          => '',
        'hash_type'     => 'md5',
        'data_compress' => false,
        'tag_prefix'    => 'tag:',
        'serialize'     => [],
    ];


    public function __construct($options)
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        if(empty($this->options['path'])){
            $this->options['path'] = RUNTIME_PATH."cache";
        }

        if (substr($this->options['path'], -1) != DIRECTORY_SEPARATOR) {
            $this->options['path'] .= DIRECTORY_SEPARATOR;
        }
    }

    public function get($key, $default = null)
    {
        $raw = $this->getRaw($key);
        return is_null($raw)?$default:$this->unserialize($raw['content']);
    }

    public function set($key, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        $expire   = $this->getExpireTime($expire);

        $file = $this->getCacheKey($key);

        $dir = dirname($file);

        if (!is_dir($dir)) {
            try {
                mkdir($dir, 0755, true);
            } catch (\Exception $e) {
                // 创建失败
            }
        }

        $data = $this->serialize($value);

        if ($this->options['data_compress'] && function_exists('gzcompress')) {
            //数据压缩
            $data = gzcompress($data, 3);
        }

        $data   = "<?php\n//" . sprintf('%012d', $expire) . "\n exit();?>\n" . $data;
        $result = file_put_contents($file, $data);

        if ($result) {
            clearstatcache();
            return true;
        }
        return false;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $val) {
            $result = $this->set($key, $val, $ttl);

            if (false === $result) {
                return false;
            }
        }
        return true;
    }

    public function clear()
    {
        $dirname = $this->options['path'] . $this->options['prefix'];

        $this->rmdir($dirname);

        return true;
    }
    public function delete($key)
    {
        return $this->unlink($this->getCacheKey($key));
    }
    public function deleteMultiple($keys)
    {
        foreach($keys as $key=>$val){
            $result = $this->delete($val);
            if(!$result){
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
        return $this->getRaw($key) !== null;
    }

    public function getCacheKey(string $name) {
        if ($this->options['prefix']) {
            $name = $this->options['prefix'] . DIRECTORY_SEPARATOR . $name;
        }

        return $this->options['path'] . $name . '.php';
    }

    protected function getRaw($name){
        $filename = $this->getCacheKey($name);

        if (!is_file($filename)) {
            return;
        }

        $content = @file_get_contents($filename);

        if (false !== $content) {
            $expire = (int) substr($content, 8, 12);
            if (0 != $expire && time() - $expire > filemtime($filename)) {
                //缓存过期删除缓存文件
                $this->unlink($filename);
                return;
            }
            $content = substr($content, 32);
            if ($this->options['data_compress'] && function_exists('gzcompress')) {
                //启用数据压缩
                $content = gzuncompress($content);
            }
            return ['content' => $content, 'expire' => $expire];
        }
    }

    /**
     * 判断文件是否存在后，删除
     * @access private
     * @param string $path
     * @return bool
     */
    private function unlink(string $path): bool
    {
        try {
            return is_file($path) && unlink($path);
        } catch (\Exception $e) {
            return false;
        }
    }

}