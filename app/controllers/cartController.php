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
    

    public function removeItem()
    {
        // Get the parameters from the request
        $id_cart_item = isset($_GET['id_cart_item']) ? (int)$_GET['id_cart_item'] : 0;
        $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 0;
    
        // Fetch the cart item details
        $cartItem = $this->cartItemModel->getCartItemById($id_cart_item);
        
        if (!$cartItem) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Cart item not found.']);
            exit();
        }
    
        // Fetch the user ID of the cart item
        $id_user = $cartItem['id_user'];
        $currentQuantity = $cartItem['quantity'];
        
        // Check if the cart item belongs to the current user
        if ($id_user !== AuthHelpers::getLoggedInUserData()['id']) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['error' => 'Forbidden']);
            exit();
        }
    
        // Determine the action based on the requested quantity
        if ($quantity < $currentQuantity) {
            // Update the cart item quantity
            $newQuantity = $currentQuantity - $quantity;
            $updateResult = $this->cartItemModel->updateCartItem($id_cart_item, $newQuantity);

            if ($updateResult) {
                echo json_encode([
                    'action' => 'update',
                    'new_quantity' => $newQuantity
                ]);
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'Failed to update cart item.']);
            }
        } elseif ($quantity >= $currentQuantity) {
            // Remove the cart item
            $deleteResult = $this->cartItemModel->removeCartItem($id_cart_item);
    
            if ($deleteResult) {
                echo json_encode([
                    'action' => 'delete'
                ]);
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'Failed to delete cart item.']);
            }
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid quantity']);
        }
    }
    

    public function updateQuantity($id_cart_item, $quantity)
    {
        return $this->cartItemModel->updateCartItem($id_cart_item, $quantity);
        jsRedirect('/cart');
    }
}
