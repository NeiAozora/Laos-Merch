<?php

define ('BASEURL', $url);
define("ROOT", dirname(dirname(dirname(__DIR__))));
define("PUBLIC_ASSETS", $url . "/public");
define("VIEWS", ROOT . "/views");
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', '');

define('CONTROLLER', "../app/controller");