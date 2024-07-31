<?php

class CartController extends Controller{
    private $cartItemModel;

    public function __construct()
    {
        $this->cartItemModel = new CartItemModel();
    }

    public function index(){
        // Ambil ID pengguna dari sesi atau parameter lainnya
        $id_user = $_SESSION['user']['uid'] ?? null;

        if ($id_user) {
            $cartItems = $this->cartItemModel->getCartItemsByUserId($id_user);
            $cartDetails = [];

            foreach($cartItems as $item){
                $combination = $this->cartItemModel->getCombination($item['id_combination']);
                $cartDetails[] = array_merge($item, $combination);
            }
            
            view('cart/index', [
                'cartItems' => $cartItems,
                'totalCost' => array_sum(array_column($cartDetails, 'total_price'))
            ]);
        } else {
            // Redirect atau tampilkan halaman error jika ID pengguna tidak ditemukan
            view('404/index');
        }
    }

    public function add($id_combination, $quantity)
    {
        $id_user = $_SESSIOn['user']['uid'] ?? null;
        if($id_user){
            $id_combination = $_POST['id_combination'];
            $quantity = $_POST['quantity'];

            $this->cartItemModel->addCartItem($id_user, $id_combination, $quantity);
            header('location:'.BASEURL.'cart');
        } else{
            view('404/index');
        }
    }

    public function removeItem($id_cart_item)
    {
        $this->cartItemModel->removeCartItem($id_cart_item);
        jsRedirect('/cart');
    }

    public function updateQuantity($id_cart_item, $quantity)
    {
        return $this->cartItemModel->updateCartItem($id_cart_item, $quantity);
        jsRedirect('/cart');
    }

}
