<?php

function dd(...$data)
{
    foreach($data as $d){
    d($d);
    die;
    }
}

function view($view, $data = [])
{
    // Extract the data array into variables
    extract($data);

    // Include the view file
    require_once 'views/' . $view . '.php';
}

function requireView($view, $data = []){
    extract($data);
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

function  formatPriceValue($value){
    return rtrim(rtrim(number_format($value, 2), '0'), '.');
}

function jsRedirect(string $urlTarget){
    echo "<script>
    window.location.href = '$urlTarget'    
    </script>";
}

function getGlobalVar($varName) {
    global $$varName;
    return isset($$varName) ? $$varName : null;
}


function getIndexByValue($array, $searchKey, $searchValue) {
    $values = array_column($array, $searchKey);
    return array_search($searchValue, $values);
}


function interpolateQuery($query, $params) {
    $keys = array();
    $values = $params;

    # build a regular expression for each parameter
    foreach ($params as $key => $value) {
        if (is_string($key)) {
            $keys[] = '/:'.$key.'/';
        } else {
            $keys[] = '/[?]/';
        }

        if (is_array($value))
            $values[$key] = implode(',', $value);

        if (is_null($value))
            $values[$key] = 'NULL';
    }
    // Walk the array to see if we can add single-quotes to strings
    array_walk($values, function(&$v, $k) { if (!is_numeric($v) && $v != "NULL") $v = "\'" . $v . "\'"; });

    $query = preg_replace($keys, $values, $query, 1, $count);

    return $query;
}