<?php

function dd(...$data)
{
    var_dump($data);
    die;
}

function view($view, $data = [])
{
   require_once 'app/views/' . $view . '.php';
}

function invokeClass($className, $methodName) : callable
{
    if (!class_exists($className)){
        throw new Exception("Target class '{$className}' tidak ada!");
    }
    if (!method_exists($className, $methodName)){
        throw new Exception("Target method '{$methodName}' dari class {$className} tidak ada!");
    }
    return function() use ($className, $methodName){
        $controller = new $className();
        call_user_func([$controller, $methodName]);
    };

}