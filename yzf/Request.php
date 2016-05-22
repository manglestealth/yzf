<?php
namespace yzf;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    private $method;
    private $get;
    private $post;
    private $headers;
    public $isAjax;
    public $cookies;
    private $url;
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->get = $_GET;
        $this->post = $_POST;
        $this->headers = $this->getHttpHeaders();
        $this->cookies = $_COOKIE;
        $this->isAjax = isset($_SERVER['X_REQUESTED_WITH']) && $_SERVER['X_REQUESTED_WITH'] == 'XMLHttpRequest';
        $this->url = ltrim($_SERVER['REQUEST_URI'],'/');
    }

    public function method()
    {
        return $this->method;
    }

    public function getHttpHeaders()
    {
        $httpHeaders = array();
        foreach( array_keys($_SERVER) as $key){
            if( substr($key,0,5) == 'HTTP_' ){
                $httpHeaders[] = $_SERVER[$key];
            }
        }
        return $httpHeaders;
    }

    public function url()
    {
        return $this->url;
    }

    public function post()
    {
        return $this->post;
    }
}