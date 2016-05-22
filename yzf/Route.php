<?php
namespace yzf;

class Route
{
   public $isMatch;

   private $method;

   private $pattern;

   private $callable;

   private $request;

   private $params;

   public function __construct($method, $pattern, $callable, Request $request)
   {
       $this->method = $method;
       $this->pattern = ltrim($pattern,'/');
       $this->callable = $callable;
       $this->request = $request;
       $this->params = array();
       $this->isMatch = false;

        if($this->pattern == $request->url()){
            $this->isMatch = true;
        }
   }

   public function matched()
   {
       return $this->isMatch;
   }

   public function method()
   {
       return $this->method;
   }

   public function callables()
   {
       return $this->callable;
   }

   public function params()
   {
       return $this->params;
   }
}