<?php
/**
 * Created by PhpStorm.
 * User: yemaozi999
 * Date: 2020/5/18
 * Time: 15:00
 */

namespace framework;


class Request
{
    protected $param = [];

    protected $mergeParam;

    protected $put;

    protected $server = [];

    protected $varMethod;

    protected $post = [];

    protected $get = [];

    protected $method;

    protected $request = [];

    protected $cookie = [];

    protected $file = [];

    protected $header = [];

    protected $input;

    public function __construct(){
        $this->input = file_get_contents('php://input');
    }

    public static function __make():Request
    {
        $request = new static();

        if (function_exists('apache_request_headers') && $result = apache_request_headers()) {
            $header = $result;
        } else {
            $header = [];
            $server = $_SERVER;
            foreach ($server as $key => $val) {
                if (0 === strpos($key, 'HTTP_')) {
                    $key          = str_replace('_', '-', strtolower(substr($key, 5)));
                    $header[$key] = $val;
                }
            }
            if (isset($server['CONTENT_TYPE'])) {
                $header['content-type'] = $server['CONTENT_TYPE'];
            }
            if (isset($server['CONTENT_LENGTH'])) {
                $header['content-length'] = $server['CONTENT_LENGTH'];
            }
        }

        $request->header = array_change_key_case($header);
        $request->server = $_SERVER;

        $inputData = $request->getInputData($request->input);

        $request->get     = $_GET;
        $request->post    = $_POST ?: $inputData;
        $request->put     = $inputData;
        $request->request = $_REQUEST;
        $request->cookie  = $_COOKIE;
        $request->file    = $_FILES ?? [];

        return $request;
    }

    protected function getInputData($content): array
    {
        $contentType = $this->contentType();
        if ($contentType == 'application/x-www-form-urlencoded') {
            parse_str($content, $data);
            return $data;
        } elseif (false !== strpos($contentType, 'json')) {
            return (array) json_decode($content, true);
        }

        return [];
    }

    /**
     * 设置或者获取当前的Header
     * @access public
     * @param  string $name header名称
     * @param  string $default 默认值
     * @return string|array
     */
    public function header(string $name = '', string $default = null)
    {
        if ('' === $name) {
            return $this->header;
        }

        $name = str_replace('_', '-', strtolower($name));

        return $this->header[$name] ?? $default;
    }

    /**
     * 当前请求 HTTP_CONTENT_TYPE
     * @access public
     * @return string
     */
    public function contentType(): string
    {
        $contentType = $this->header('Content-Type');

        if ($contentType) {
            if (strpos($contentType, ';')) {
                [$type] = explode(';', $contentType);
            } else {
                $type = $contentType;
            }
            return trim($type);
        }

        return '';
    }

    /**
     * 获取当前请求的参数
     * @access public
     * @param  string|array $name 变量名
     * @param  mixed        $default 默认值
     * @return mixed
     */
    public function param($name = '', $default = null)
    {
        if (empty($this->mergeParam)) {
            $method = $this->method(true);

            // 自动获取请求变量
            switch ($method) {
                case 'POST':
                    $vars = $this->post(false);
                    break;
                case 'PUT':
                case 'DELETE':
                case 'PATCH':
                    $vars = $this->put(false);
                    break;
                default:
                    $vars = [];
            }

            // 当前请求参数和URL地址中的参数合并
            $this->param = array_merge($this->param, $this->get(false), $vars);

            $this->mergeParam = true;
        }

        if (is_array($name)) {
            return $this->only($name, $this->param);
        }

        return $this->input($this->param, $name, $default);
    }

    /**
     * 获取指定的参数
     * @access public
     * @param  array        $name 变量名
     * @param  mixed        $data 数据或者变量类型
     * @return array
     */
    public function only(array $name, $data = 'param'): array
    {
        $data = is_array($data) ? $data : $this->$data();

        $item = [];
        foreach ($name as $key => $val) {

            if (is_int($key)) {
                $default = null;
                $key     = $val;
                if (!isset($data[$key])) {
                    continue;
                }
            } else {
                $default = $val;
            }

            $item[$key] = $data[$key] ?? $default;
        }

        return $item;
    }


    /**
     * 获取变量 支持过滤和默认值
     * @access public
     * @param  array        $data 数据源
     * @param  string|false $name 字段名
     * @param  mixed        $default 默认值
     * @return mixed
     */
    public function input(array $data = [], $name = '', $default = null)
    {
        if (false === $name) {
            // 获取原始数据
            return $data;
        }

        $name = (string) $name;
        if ('' != $name) {
            // 解析name
            if (strpos($name, '/')) {
                [$name, $type] = explode('/', $name);
            }

            $data = $this->getData($data, $name);

            if (is_null($data)) {
                return $default;
            }

            if (is_object($data)) {
                return $data;
            }
        }

        return $data;
    }


    /**
     * 获取数据
     * @access public
     * @param  array  $data 数据源
     * @param  string $name 字段名
     * @param  mixed  $default 默认值
     * @return mixed
     */
    protected function getData(array $data, string $name, $default = null)
    {
        foreach (explode('.', $name) as $val) {
            if (isset($data[$val])) {
                $data = $data[$val];
            } else {
                return $default;
            }
        }

        return $data;
    }

    /**
     * 获取PUT参数
     * @access public
     * @param  string|array $name 变量名
     * @param  mixed        $default 默认值
     * @param  string|array $filter 过滤方法
     * @return mixed
     */
    public function put($name = '', $default = null)
    {
        if (is_array($name)) {
            return $this->only($name, $this->put);
        }

        return $this->input($this->put, $name, $default);
    }

    /**
     * 获取server参数
     * @access public
     * @param  string $name 数据名称
     * @param  string $default 默认值
     * @return mixed
     */
    public function server(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->server;
        } else {
            $name = strtoupper($name);
        }

        return $this->server[$name] ?? $default;
    }

    /**
     * 当前的请求类型
     * @access public
     * @param  bool $origin 是否获取原始请求类型
     * @return string
     */
    public function method(bool $origin = false): string
    {
        if ($origin) {
            // 获取原始请求类型
            return $this->server('REQUEST_METHOD') ?: 'GET';
        } elseif (!$this->method) {
            if (isset($this->post[$this->varMethod])) {
                $method = strtolower($this->post[$this->varMethod]);
                if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
                    $this->method    = strtoupper($method);
                    $this->{$method} = $this->post;
                } else {
                    $this->method = 'POST';
                }
                unset($this->post[$this->varMethod]);
            } elseif ($this->server('HTTP_X_HTTP_METHOD_OVERRIDE')) {
                $this->method = strtoupper($this->server('HTTP_X_HTTP_METHOD_OVERRIDE'));
            } else {
                $this->method = $this->server('REQUEST_METHOD') ?: 'GET';
            }
        }

        return $this->method;
    }

    /**
     * 获取POST参数
     * @access public
     * @param  string|array $name 变量名
     * @param  mixed        $default 默认值
     * @return mixed
     */
    public function post($name = '', $default = null)
    {
        if (is_array($name)) {
            return $this->only($name, $this->post);
        }

        return $this->input($this->post, $name, $default);
    }

    /**
     * 获取GET参数
     * @access public
     * @param  string|array $name 变量名
     * @param  mixed        $default 默认值
     * @param  string|array $filter 过滤方法
     * @return mixed
     */
    public function get($name = '', $default = null)
    {
        if (is_array($name)) {
            return $this->only($name, $this->get);
        }

        return $this->input($this->get, $name, $default);
    }
}