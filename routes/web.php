<?php

// API
Router::get("/api/products", invokeClass(ProductController::class, "list"));
Router::get("/api/reviews", invokeClass(ReviewController::class, "getReviews"));



Router::get('/auth-process', invokeClass(LoginController::class, "processAuth"));
Router::get('/logout', invokeClass(LoginController::class, "logout"));
// Homepage
Router::get('/', invokeClass(IndexController::class, "index"));
// Product
Router::get('/product/{id}', invokeClass(ProductController::class, "getProduct"));
// UserProfile
Router::get('/user/{firebaseId}/profile', function() {
    
    view("/404/index");
});
// UserSettings
Router::get('/user/{firebaseId}/settings', function() {
    view("/404/index");
});
// Keranjang
Router::get('/carts', function() {
    view("404/index");
});
// Checkout
Router::get('/user/{firebaseId}/settings', function() {
    view("/404/index");
});
// Riwayat pesanan-pesanan
Router::get('/purchase', function() {
    view("/404/index");
});
// Detail Pesanan
Router::get('/purchase/order', function() {
    view("/404/index");
});




Router::get("/about/privacy-policy", function(){
    view("/about/privacy-policy/index");
});

Router::get('/login', invokeClass(LoginController::class, "index"));
Router::get('/order', invokeClass(OrderController::class, "index"));
Router::get('/cart', invokeClass(CartController::class, "index"));
Router::get('/checkout', invokeClass(CheckoutController::class, "index"));
Router::get('/order/detail', invokeClass(OrderDetailController::class, "index"));
Router::get("/service/help-center", invokeClass(HelpCenterController::class, "index"));



// Custom 404 Not Found handler
Router::notFound(function() {
    view("/404/index");
});
?>
