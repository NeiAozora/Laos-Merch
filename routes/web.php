<?php



// Sisi Customer

// Homepage
Router::get('/', invokeClass(IndexController::class, "index"));
// UserProfile
Router::get('/user/{google_id}/profile', function() {
    
    view("/404/index");
});
// UserSettings
Router::get('/user/{google_id}/settings', function() {
    view("/404/index");
});


Router::get('/login', invokeClass(LoginController::class, "index"));
Router::get('/product/{id}', invokeClass(ProductController::class, "index"));




// Custom 404 Not Found handler
Router::notFound(function() {
    view("/404/index");
});
?>
