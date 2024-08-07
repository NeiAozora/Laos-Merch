<?php
class CartController extends Controller
{
    private $cartItemModel;

    public function __construct()
    {
        $this->cartItemModel = new CartItemModel();
    }

    public function index()
    {
        $id_user = $_SESSION['user']['id_user'] ?? null;

        if ($id_user) {
            $cartItems = $this->cartItemModel->getCartItemsByUserId($id_user);
            $cartDetails = [];

            foreach ($cartItems as $item) {
                $combination = $this->cartItemModel->getCombination($item['id_combination']);
                $cartDetails[] = array_merge($item, $combination);
            }

            view('cart/index', [
                'cartItems' => $cartItems,
                'totalCost' => array_sum(array_column($cartDetails, 'total_price'))
            ]);
        } else {
            view('404/index');
        }
    }

    public function add()
    {
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if ($id_user) {
            $id_combination = $_POST['id_combination'];
            $quantity = $_POST['quantity'];
    
            // Check if the cart item already exists
            $existingCartItem = $this->cartItemModel->getCartItemByUserAndCombination($id_user, $id_combination);
    
            if ($existingCartItem) {
                // Update the existing cart item quantity
                $newQuantity = $existingCartItem['quantity'] + $quantity;
                $this->cartItemModel->updateCartItem($existingCartItem['id_cart_item'], $newQuantity);
            } else {
                // Insert a new cart item
                $this->cartItemModel->addCartItem($id_user, $id_combination, $quantity);
            }
    
            header('Location: ' . BASEURL . 'cart');
        } else {
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
