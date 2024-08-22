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

// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
define('PRODUCTION_MODE', false); 

define('CONTROLLER', "../app/controller");


// FIXING INCOSYSTENCY OF DATETIME PROBLEM
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the MySQL session time zone
$result = $conn->query("SELECT @@session.time_zone AS time_zone");
$row = $result->fetch_assoc();
$mysqlTimeZone = $row['time_zone'];

// Set the PHP time zone to match the MySQL time zone
if ($mysqlTimeZone !== 'SYSTEM') {
    date_default_timezone_set($mysqlTimeZone);
} else {
    // If SYSTEM is used, PHP should use the server's system time zone
    date_default_timezone_set(ini_get('date.timezone'));
}