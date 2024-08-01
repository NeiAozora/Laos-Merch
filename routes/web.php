<?php

Router::getInstance()->addGlobalMiddleware(function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
});


// API
Router::get("/api/products", invokeClass(ProductController::class, "list"));
Router::get("/api/reviews", invokeClass(ReviewController::class, "getReviews"));



Router::get('/auth-process', invokeClass(LoginController::class, "processAuth"));

Router::get('/register', invokeClass(RegisterController::class, 'index'));
Router::post('/register', invokeClass(RegisterController::class, 'processRegister'));
Router::get('/auth-verification', invokeClass(RegisterController::class, 'verificationIndex'));
Router::post('/auth-verification', invokeClass(RegisterController::class, 'postPageVerification'));

Router::get("/recovery", invokeClass(RecoveryController::class, 'index'));
Router::post("/recovery", invokeClass(RecoveryController::class, 'process'));

Router::get('/logout', invokeClass(LoginController::class, "logout"));
// Homepage
Router::get('/', invokeClass(IndexController::class, "index"));
// Product
Router::get('/product/{id}', invokeClass(ProductController::class, "getProduct"));
// UserProfile
Router::get('/user/{firebaseId}/profile', invokeClass(ProfileController::class, "profileSettings"));
// UserSettings
Router::get('/user/{firebaseId}/settings', function () {
    view("/404/index");
});
// Keranjang
Router::get('/carts', function () {
    view("404/index");
});
// Checkout
Router::get('/user/{firebaseId}/settings', function () {
    view("/404/index");
});
// Riwayat pesanan-pesanan
Router::get('/purchase', function () {
    view("/404/index");
});
// Detail Pesanan
Router::get('/purchase/order', function () {
    view("/404/index");
});

// Keranjang
Router::get('/cart', invokeClass(CartController::class, "index"));
Router::post('/cart/add', invokeClass(CartController::class, 'add'));
// Router::post('/cart/remove', function() {
//     $id_cart_item = $_POST['id_cart_item'];
//     invokeClass(CartController::class, 'removeItem', [$id_cart_item]);
// });
// Router::post('/cart/update', function() {
//     $id_cart_item = $_POST['id_cart_item'];
//     $quantity = $_POST['quantity'];
//     invokeClass(CartController::class, 'updateQuantity', [$id_cart_item, $quantity]);
// });



Router::get("/about/privacy-policy", function () {
    view("/about/privacy-policy/index");
});

Router::get('/login', invokeClass(LoginController::class, "index"));
Router::get('/order', invokeClass(OrderController::class, "index"));

Router::get('/checkout', invokeClass(CheckoutController::class, "index"));
Router::get('/order/detail', invokeClass(OrderDetailController::class, "index"));
Router::get("/service/help-center", invokeClass(HelpCenterController::class, "index"));


Router::get("/notfound", function () {
    view("/404/index");
});
Router::get("/error", function () {

    if (!isset($_GET["code"]) && !isset($_GET["message"]) && !isset($_GET["detailMessage"])) {
        jsRedirect("/notfound");
    }

    view("/customerror/index", ['errorCode' => $_GET['code'], 'errorMessage' => $_GET['message'], 'detailMessage' => ($_GET["detailMessage"])]);
});
// Custom 404 Not Found handler
Router::notFound(function () {
    view("/404/index");
});
