<?php

// Define a constant for WEB_DOMAIN_MODE
define('WEB_DOMAIN_MODE', true); // Set this to true or false as needed

$host = $_SERVER['HTTP_HOST'];



if (WEB_DOMAIN_MODE) {
    define ('BASEURL', "https://laosmerch.neiaozora.my.id/");
} else {
// Determine the base URL based on the conditions
    if (strpos($host, ':8080') !== false) {
        define('BASEURL', "http://localhost:8080/Laos-Merch/");
    } else {
        define('BASEURL', "http://localhost/Laos-Merch/");
    }
}
define("ROOT", dirname(dirname(__DIR__)));
define("VIEWS", ROOT . "/views/");
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laos_merch');

define('CONTROLLER', "../app/controller");