<?php


namespace framework\core\filesystem\driver;


use framework\core\filesystem\Driver;
use League\Flysystem\AdapterInterface;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;

class Qiniu extends Driver
{
    protected $config = [
        'accessKey ' => '',
        'secretKey ' => '',
        'bucket ' => '',
        'domain ' =>'',
    ];

    protected function createAdapter(): AdapterInterface
    {
        return new QiniuAdapter(
            $this->config['accessKey'],
            $this->config['secretKey'],
            $this->config['bucket'],
            $this->config['domain']
        );
    }
}