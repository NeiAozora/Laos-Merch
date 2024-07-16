<?php

Router::get('/test/{custom_value}/test', function($custom_value) {
    echo "Custom Value: " . htmlspecialchars($custom_value);
});


Router::getInstance()->addGlobalMiddleware(function(){
    // echo "ini middleware global<br>";
});

Router::get('/', invokeClass(IndexController::class, "index"));
Router::get('/product/{id}', invokeClass(ProductController::class, "index"));


// Custom 404 Not Found handler
Router::notFound(function() {
    view("/404/index");
});
?>
