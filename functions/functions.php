<?php

function dd(...$data)
{
    foreach($data as $d){
        var_dump($d);
    }
    die;
}

function view($view, $data = [])
{
    // Extract the data array into variables
    extract($data);

    // Include the view file
    require_once 'views/' . $view . '.php';
}

function invokeClass($className, $methodName) : callable
{
    if (!class_exists($className)){
        throw new Exception("Target class '{$className}' tidak ada!");
    }
    if (!method_exists($className, $methodName)){
        throw new Exception("Target method '{$methodName}' dari class {$className} tidak ada!");
    }
    return function(...$data) use ($className, $methodName){
        $controller = new $className();
        call_user_func_array([$controller, $methodName], $data);
    };

}


function jsRedirect(string $urlTarget){
    echo "<script>
    window.location.href = '$urlTarget'    
    </script>";
}