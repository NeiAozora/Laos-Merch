<?php


// Router::
// Sisi Customer

Router::get('/auth-process', invokeClass(LoginController::class, "processAuth"));
// Homepage
Router::get('/', invokeClass(IndexController::class, "index"))
->addMiddleware(invokeClass(AuthMiddleware::class, "checkLoginSession"));

// UserProfile
Router::get('/user/{firebaseId}/profile', function() {
    
    view("/404/index");
});
// UserSettings
Router::get('/user/{firebaseId}/settings', function() {
    view("/404/index");
});
// Keranjang
Router::get('/cart', function() {
    view("/404/index");
});
// Checkout
Router::get('/user/{firebaseId}/settings', function() {
    view("/404/index");
});
// Riwayat pesanan-pesanan
Router::get('/purcase', function() {
    view("/404/index");
});
// Detail Pesanan
Router::get('/purcase/order', function() {
    view("/404/index");
});




Router::get("/about/privacy-policy", function(){
    view("/about/privacy-policy/index");
});

Router::get('/login', invokeClass(LoginController::class, "index"));
Router::get('/product/{id}', invokeClass(ProductController::class, "index"));




// Custom 404 Not Found handler
Router::notFound(function() {
    view("/404/index");
});
?>
