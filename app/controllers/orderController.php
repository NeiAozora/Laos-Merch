<?php

class OrderController extends Controller{
    private $orderModel;

    public function __construct(){
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $id_user = $_SESSION['user']['id_user'] ?? null;
    
        if ($id_user) {
            $statusMapping = [
                'Semua' => null,
                'Diproses' => 'Processing',
                'Dikirim' => 'Shipped',
                'Selesai' => 'Delivered',
                'Dibatalkan' => 'Cancelled'
            ];
    
            // Reverse mapping for displaying status in Indonesian
            $reverseStatusMapping = [
                null => 'Semua',
                'Processing' => 'Diproses',
                'Shipped' => 'Sedang Dikirim',
                'Delivered' => 'Selesai',
                'Cancelled' => 'Dibatalkan'
            ];
    
            $status = isset($_GET['status']) ? $_GET['status'] : 'Semua';
            $statusDb = $statusMapping[$status] ?? null;
    
            // Log status yang diterima
            error_log("Selected Status: " . $status);
            error_log("Mapped Status: " . $statusDb);
    
            $orders = $this->orderModel->getAllOrders($id_user, $statusDb);
    
            // Loop through orders to update the status to Indonesian
            foreach ($orders as &$order) {
                $order['status_name'] = $reverseStatusMapping[$order['status_name']] ?? 'Semua';
            }
            unset($order); // Unset reference to avoid unintended side effects

            view('order/index', ['orders' => $orders, 'status' => $status]);
        } else {
            view('404/index');
        }
    }
    
    
    
    public function detail($id){
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if($id_user){
            $order = $this->orderModel->getOrderById($id, $id_user);
            d($order);
            die;
            if($order){
                view('orderdetail/index', ['order' => $order]);
            }else{
                view('404/index');
            }
        }else{
            view('404/index');
        }
    }


    public function updateStatus(){
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if($id_user && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $id_order = $_POST['id_order'] ?? null;
            $status = $_POST['status'] ?? null;

            if($id_order && $status){
                $arrStatus = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];
                if(in_array($status, $arrStatus)){
                    if ($this->orderModel->updateOrderStatus($id_order, $status)) {
                        // Status berhasil diubah, arahkan ulang ke halaman pesanan
                        header("Location: " . BASEURL . "order");
                        exit;
                    } else {
                        // Gagal memperbarui status di database
                        $_SESSION['error'] = 'Failed to update order status in database.';
                    }
                } else {
                    // Status tidak valid
                    $_SESSION['error'] = 'Invalid status received.';
                }
            } else {
                // Parameter tidak lengkap
                $_SESSION['error'] = 'Missing parameters: id_order or status';
            }
        } else {
            // Permintaan tidak sah atau metode permintaan tidak valid
            $_SESSION['error'] = 'Unauthorized request or invalid request method.';
        }

        // Jika terjadi kesalahan, arahkan ulang ke halaman pesanan dengan pesan kesalahan
        header("Location: " . BASEURL . "order");
        exit;
    }

    public function prepareOrder() {
        $user = AuthHelpers::getLoggedInUserData();
        
        if (empty($user)) {
            $this->sendError('Forbidden', 403);
        }
    
        $requiredKeys = ['shippingMethodId', 'paymentMethodId', 'idShippingAddress', 'token', 'selectedProductsParameters'];
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError('Invalid JSON', 400);
        }
    
        foreach ($requiredKeys as $key) {
            if (empty($data[$key])) {
                $this->sendError('Forbidden', 403);
            }
        }
        
        $shippingMethodId = $data['shippingMethodId'];
        $paymentMethodId = $data['paymentMethodId'];
        $idShippingAddress = $data['idShippingAddress'];
        $token = $data['token'];
        $selectedProductsParameters = $data['selectedProductsParameters'];
    
        $verifiedIdToken = AuthHelpers::verifyFBAcessIdToken($token);
    
        if (empty($verifiedIdToken)) {
            $this->sendError('Forbidden', 403);
        }
        
        function decode_obfuscated_data($data) {
            return base64_decode($data);
        }
        
        parse_str($selectedProductsParameters, $params);
        
        if (isset($params['p']) && isset($params['q'])) {
            $productIdsEncoded = $params['p'];
            $quantitiesEncoded = $params['q'];
    
            $productIds = explode(',', decode_obfuscated_data($productIdsEncoded));
            $quantities = explode(',', decode_obfuscated_data($quantitiesEncoded));
    
            if (count($productIds) !== count($quantities)) {
                $this->sendError('Invalid product parameters', 400);
            }
    
            $productDetails = ProductModel::new()->getProductsByCombinations($productIds);
    
            $products = [];
            $totalPrice = 0;
    
            foreach ($productDetails as $detail) {
                $productId = $detail['id_product'];
                $quantity = $quantities[array_search($productId, $productIds)];
    
                if ($quantity > 0) {
                    $finalPrice = !empty($detail['discount_value']) && $detail['discount_value'] > 0
                        ? $detail['price'] * (1 - ($detail['discount_value'] / 100))
                        : $detail['price'];
    
                    $totalPrice += $quantity * $finalPrice;
    
                    $products[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'product_name' => $detail['product_name'],
                        'image_url' => $detail['product_image'],
                        'selected_options' => $detail['selected_options'],
                        'price' => $detail['price'],
                        'discount_value' => $detail['discount_value'],
                        'final_price' => $finalPrice
                    ];
                }
            }
    
        } else {
            $this->sendError('Missing product parameters', 400);
        }
    
        OrderModel::new()->deleteOrdersByInterval($user['id'], '30m');
    
        $orderData = [
            'id_user' => $user['id'],
            'id_status' => 1,
            'total_price' => $totalPrice,
            'shipping_fee' => 0,
            'service_fee' => 2.50,
            'handling_fee' => 1.50,
            'id_payment_method' => $paymentMethodId,
            'id_shipping_address' => $idShippingAddress
        ];
    
        $idOrder = OrderModel::new()->insertOrder($orderData);
    
        $response = [
            'success' => true,
            'snapToken' => '',
            'redirect' => ''
        ];
    
        if ($paymentMethodId == 2) {  // Transfer
            $transactionDetails = [
                'order_id' => $idOrder,
                'service_fee' => 2500,
                'handling_fee' => 1500,
                'gross_amount' => (int) $totalPrice,
            ];
    
            $itemDetails = [];
    
            foreach ($products as $product) {
                $itemDetails[] = [
                    'id' => $product['product_id'],
                    'price' => (int)$product['final_price'],
                    'quantity' => (int) $product['quantity'],
                    'name' => $product['product_name']
                ];
            }
    
            $firebaseId = $verifiedIdToken->claims()->get("sub");
            $user = UserModel::new()->get(['id_firebase', $firebaseId]);
    
            if (empty($user)) {
                $this->sendError('User not found in local database', 404);
            }
    
            $user = $user[0];
    
            $shippingAddress = ShippingAddressModel::new()->get($idShippingAddress);
    
            if (empty($shippingAddress)) {
                $this->sendError('Shipping address not found', 404);
            }
    
            $customerDetails = [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'address' => $shippingAddress['street_address'],
                'city' => $shippingAddress['city'],
                'state' => $shippingAddress['state'],
                'postal_code' => $shippingAddress['postal_code'],
                'Whatsapp number' => $user['wa_number'],
                'country_code' => 'IDN'
            ];
    
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails
            ];
    
            $response['snapToken'] = \Midtrans\Snap::getSnapToken($params);
    
        } elseif ($paymentMethodId == 1) {  // COD
            $response['redirect'] = BASEURL . 'order/detail/' . $idOrder;
        }
    
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    
    private function sendError($message, $statusCode) {
        header("HTTP/1.1 $statusCode $message");
        echo json_encode(['error' => $message]);
        exit();
    }
    
    
    
}