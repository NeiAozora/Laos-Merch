<?php

class CartController extends COntroller{
    private $cartItemModel;

    public function __construct()
    {
        $this->cartItemModel = new CartItemModel();
    }

    public function index(){
        // Ambil ID pengguna dari sesi atau parameter lainnya
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if ($id_user) {
            $cartItems = $this->cartItemModel->getCartItemsByUserId($id_user);
            $totalCost = $this->cartItemModel->getTotalCostByUserId($id_user);
            view('cart/index', [
                'cartItems' => $cartItems,
                'totalCost' => $totalCost
            ]);
        } else {
            // Redirect atau tampilkan halaman error jika ID pengguna tidak ditemukan
            view('404/index', ['message' => 'User not logged in']);
        }
    }

    public function addItem($id_combination, $quantity)
    {
        $id_user = $_SESSIOn['id_user'] ?? null;
        if($id_user){
            $this->cartItemModel->createCartItem($id_user, $id_combination, $quantity);
            jsRedirect('/cart');
        }else{
            view('404/index', ['message' => 'User not logged in']);
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
