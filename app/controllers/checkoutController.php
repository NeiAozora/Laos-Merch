<?php

class CheckoutController extends Controller {
    private $productModel;
    private $shipmentMethodModel;
    private $paymentModel;
    
    public function __construct() {
        $this->productModel = new ProductModel();
        $this->shipmentMethodModel = new ShipmentMethodModel(); // Assuming you have a ShipmentMethodModel
        $this->paymentModel = new PaymentMethodModel(); // Assuming you have a PaymentModel
    }
    
    public function index() {
        // Define a method to decode obfuscated data
        function decode_obfuscated_data($data) {
            // Example obfuscation: Base64 encoding
            return base64_decode($data);
        }

        
        // Example obfuscation: Base64 encoding for parameters
        if (isset($_GET['p']) && isset($_GET['q'])) {
            // Decode the obfuscated parameters
            $productIdsEncoded = $_GET['p'];
            $quantitiesEncoded = $_GET['q'];

            $productIds = explode(',', decode_obfuscated_data($productIdsEncoded));
            $quantities = explode(',', decode_obfuscated_data($quantitiesEncoded));

            // Validate the lengths of the arrays
            if (count($productIds) === count($quantities)) {
                // Fetch product details
                $products = [];
                $totalPrice = 0;

                // Fetch product details in one query (if possible)
                $productDetails = $this->productModel->getProductsByCombinations($productIds); // Adjust as necessary

                foreach ($productDetails as $detail) {
                    $productId = $detail['id_product'];
                    $quantity = $quantities[array_search($productId, $productIds)];
                    if ($quantity > 0) { // Ensure quantity is not 0
                        $totalPrice += $quantity * ($detail['price'] * (1 - ($detail['discount_value'] / 100)));
                        $products[] = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'product_name' => $detail['product_name'],
                            'image_url' => $detail['product_image'],
                            'selected_options' => $detail['selected_options'],
                            'price' => $detail['price'],
                            'discount_value' => $detail['discount_value']
                        ];
                    }
                }

                // Fetch shipping methods and payment methods
                // $shippingMethods = $this->shipmentMethodModel->get(1); // memaksakan memakai COD
                // $shippingMethods = [$shippingMethods];

                $shippingMethods = $this->shipmentMethodModel->getAll();

                $paymentMethods = $this->paymentModel->getAll();

                // Fetch user information (for example purposes)
                $user = AuthHelpers::getLoggedInUserData();
                
                if(is_null($user)){
                    header('Location: /login');
                    exit;
                }

                

                // Pass data to the view
                $this->view('checkout/index', [
                    'products' => $products,
                    'shipping_methods' => $shippingMethods,
                    'payment_methods' => $paymentMethods,
                    'user' => $user,
                    'total_price' => $totalPrice
                ]);
            } else {
                // Redirect back if IDs and quantities do not match
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        } else {
            // Redirect back if expected parameters are missing
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }



}
