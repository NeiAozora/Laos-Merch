<?php

Router::getInstance()->addGlobalMiddleware(function () {
    if (session_status() == PHP_SESSION_NONE) {
        // ASli Kalau ERROR NYA DISII AKU GAK TAU ERROR XAMPP NYA
        session_start();
    }
});


// API
Router::get("/api/products", invokeClass(ProductController::class, "list"));
Router::get("/api/reviews", invokeClass(ReviewController::class, "getReviews"));
Router::post("/api/get-mini-cart", invokeClass(CartController::class, 'getSimpleCartData'));
Router::get("/api/reviews/product/{id}", invokeClass(ReviewController::class, "getReviewsByProductId"));
Router::get("/api/user/addresses", invokeClass(CheckoutController::class, 'getUserAddresses'));
Router::get("/api/user/addresses/{uid}/update", invokeClass(CheckoutController::class, 'getUserAddresses'));
Router::post("/api/prepare-order", invokeClass(OrderController::class, 'prepareOrder'));
Router::get("/api/user/addresses/{id}/set-primary", invokeClass(ProfileController::class, 'updateShippingAddressPriority'));
Router::post('/api/finalize-transfer-order', invokeClass(OrderController::class, 'finalizeTranferOrder'));
Router::post('/user/profile/process-crud-addresses', invokeClass(ProfileController::class, "performCRUD"));

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
Router::get('/user/{id}/profile', invokeClass(ProfileController::class, "profileSettings"));
//UpdateUser
Router::post('/user/{id_user}/profile/update', invokeClass(ProfileController::class, "updateProfile"));


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

Router::post('/review/submit', invokeClass(ReviewController::class, 'submitReview'));


// Keranjang
Router::get('/cart', invokeClass(CartController::class, "index"));
Router::post('/cart/add', invokeClass(CartController::class, 'add'));
Router::get('/cart/remove', invokeClass(CartController::class, 'removeItem'));


//Pesanan
Router::get('/order', invokeClass(OrderController::class, "index"));
Router::post('/prepareOrder', invokeClass(OrderController::class, "prepareOrder"));
Router::post('/order/updateStatus', invokeClass(OrderController::class, "updateStatus"));
Router::get('/order/detail/{id}', invokeClass(OrderController::class, "detail"));



//Atmin
Router::get('/admin', invokeClass(AdminController::class, "index"));

Router::get("/about/privacy-policy", function () {
    view("/about/privacy-policy/index");
});

Router::get('/login', invokeClass(LoginController::class, "index"));
Router::get('/checkout', invokeClass(CheckoutController::class, "index"));
Router::get("/service/help-center", invokeClass(HelpCenterController::class, "index"));


Router::get("/notfound", function () {
    http_response_code(404);

    view("/404/index");
});

Router::get("/error", function () {
    if (!isset($_GET["code"]) && !isset($_GET["message"]) && !isset($_GET["detailMessage"])) {
        jsRedirect("/notfound");
    }
    http_response_code($_GET['code']);
    view("/customerror/index", ['errorCode' => $_GET['code'], 'errorMessage' => $_GET['message'], 'detailMessage' => ($_GET["detailMessage"])]);
});
// Custom 404 Not Found handler
Router::notFound(function () {
    http_response_code(404);
    view("/404/index");
});
