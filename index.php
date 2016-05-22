<?php
function loader($class)
{
    if(preg_match('/\\\\/',$class)){
        $classPath = preg_replace('/\\\\/',DIRECTORY_SEPARATOR,$class);
        $fileName = $classPath . '.php';
        require "$fileName";
    }else{
        require $class . '.php';
    }
}

spl_autoload_register('loader');

$app = new \yzf\Yzf();


$app->map('GET','/a/b',function(){
    echo 'a/b';
});
$app->map('POST','/',function() use ($app){
    var_dump($app->request()->post());
});
$app->run();