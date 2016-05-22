<?php
namespace yzf;

class Router
{
    private $routes = array();

    private $matched;

    private $request;

    private $params;

    private $notFound;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->notFound(function(){
           echo "not found the match route";
        });
    }

    public function map($method, $pattern, $callable)
    {
        $route = new Route($method, $pattern, $callable, $this->request);
        if($route->isMatch){
            $this->matched = $route;
            $this->params = $route->params();
        }
        $this->routes[$method][] = $route;
    }

    public function dispatch()
    {
        if(!is_null($this->matched)){
            $callable = $this->matched->callables();
            if(is_callable($callable)){
                call_user_func_array($callable, array_values($this->params));
                return true;
            }
        }
        return false;
    }

    public function notFound( $callable = null)
    {
        if(is_callable($callable)){
            $this->notFound = $callable;
        }
        return $this->notFound;
    }

}