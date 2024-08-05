<?php


class App {
    public function __construct() {
        $this->initRoutes();

        $whoops = new \Whoops\Run;
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        Router::setExceptionHandler(function(Throwable $e) use ($whoops){
            http_response_code(500);
            echo $whoops->handleException($e);
        });
        Router::getInstance()->dispatch(); // Dispatch using Router
    }

    private function initRoutes() {
        require_once dirname(dirname(dirname(__FILE__))) . '/routes/web.php'; // Ensure to include the Router class
    }


}
