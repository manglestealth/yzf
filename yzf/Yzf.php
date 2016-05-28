<?php
namespace yzf;

class Yzf
{
    private $request;

    private $response;

    public $router;

    private $methods = [
        'GET',
        'POST'
    ];

    private $method;

    private $before;

    private $after;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request);
        $this->before = array();
        $this->after = array();
    }

    public function map($method, $pattern, $callable)
    {
//        if(in_array(strtoupper($method),$this->methods))
//        {
//            throw new \Exception("could not find matched http method");
//        }
        $this->method = $method;
        $this->router->map($method, $pattern, $callable);
    }

    public function request()
    {
        return $this->request;
    }

    public function contentType($type)
    {
        $this->response->header('Cotent-Type',$type);
    }

    public function status($status)
    {
        $this->response->status($status);
    }

    public function error($stauts = 500, $body = '')
    {
        $this->response->status($stauts);
        $this->response->body($body);
        $this->stop();
    }

    public function stop()
    {
        $this->response->send();
        exit;
    }
    private function runCallables($callables)
    {
        foreach ($callables as $callable){
            if(is_callable($callable)){
                $callable();
            }
        }
    }

    public function run()
    {
        $this->runCallables( $this->before );
        ob_start();
        if(!$this->router->dispatch()){
            $notFoundCallable = $this->router->notFound();
            $notFoundCallable();
            $notFound = ob_get_clean();
            $this->error(404, $notFound);
        }
        $this->response->write(ob_get_contents());
        ob_end_clean();
        $this->response->send();
        $this->runCallables( $this->after );

    }

    public function before( $callable )
    {
        $this->before[] = $callable;
    }

    public function after( $callable )
    {
        $this->after[] = $callable;
    }
    
}