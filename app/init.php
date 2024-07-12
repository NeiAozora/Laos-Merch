<?php 

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
require_once 'core/Middleware.php';
require_once 'core/Router.php';

require_once 'config/Config.php';
require_once 'helpers/functions.php';

// Otomatis include semua controllers
foreach (glob(__DIR__ . "/controllers/" . "*.php") as $file) {
    include $file;
}

// Otomatis include semua models
foreach (glob(__DIR__ . "/models/" . "*.php") as $file) {
    include $file;
}

// Otomatis include semua middlewares
foreach (glob(__DIR__ . "/middlewares/" . "*.php") as $file) {
    include $file;
}

