<?php

function dd(...$data)
{
    d($data);
    die;
}

function view($view, $data = [])
{
    // Extract the data array into variables
    extract($data);

    // Include the view file
    require_once 'views/' . $view . '.php';
}

function requireView($view){
    try {
        require_once VIEWS . $view;
    } catch (\Throwable $th) {
        throw $th;
        die;
    }
}


function isNullOrFalse($value){
    if(is_null($value)){
        return true;
    }

    if($value === false){
        return true;
    }

    return false;
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

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function jsRedirect(string $urlTarget){
    echo "<script>
    window.location.href = '$urlTarget'    
    </script>";
}