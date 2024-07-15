<?php



Router::getInstance()->addGlobalMiddleware(function(){
    // echo "ini middleware global<br>";
});

Router::get('/', invokeClass(IndexController::class, "index"));



// Custom 404 Not Found handler
Router::notFound(function() {
    view("/404/index");
});
?>
