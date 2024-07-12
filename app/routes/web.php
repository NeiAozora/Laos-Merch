<?php



Router::getInstance()->addGlobalMiddleware(function(){
    echo "ini middleware global<br>";
});

Router::get('/', invokeClass(IndexController::class, "index"));

Router::get('/about', function(){
    echo "ini halaman about<br>";
});

Router::get("/test-middleware", function(){
    echo "ini eksekusi<br>";
})->addMiddleware(invokeClass(ContohMiddleware::class, "handle"));

// Custom 404 Not Found handler
Router::notFound(function() {
    http_response_code(404);
    echo '<h1>Custom 404 Not Found</h1><p>The page you are looking for is not available.</p>';
});
?>
